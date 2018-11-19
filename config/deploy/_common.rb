#### GIT BRANCH HACK ####
set :branch, "master"
if ENV['branch']
   set :branch, ENV['branch'] || 'master'
end

#### VARIABLES SETTINGS ####
#set :scm, :git
set :format, :pretty
set :log_level, :info

set :writable_dirs, ["var/cache", "var/logs", "mail_spool", "reports", "web/uploads/media"]
set :webserver_user, "www-data"

set :permission_method, :acl
set :file_permissions_users, ["www-data"]
set :file_permissions_paths, ["var/cache", "var/logs", "web/uploads/media", "web/js"] #"web/uploads"

set :dump_assetic_assets, true

set :keep_releases, 8
########


#### SHARED DIRECTORIES/FILES ####
set :linked_files, %w{app/config/parameters.yml}
set :linked_dirs, %w{var/logs web/uploads web/js} #web/uploads --> créé le dossier
########

#### GIT local try ####
# release id is just the commit hash used to create the tarball.
set :project_release_id, `git log --pretty=format:'%h' -n 1 HEAD`
# the same path is used local and remote... just to make things simple for who wrote this.
set :project_tarball_path, "/tmp/#{fetch(:application)}-#{fetch(:project_release_id)}.tar.gz"

#### test revision git ###

set :project_revision_git, `git rev-parse #{fetch(:branch)}`

#### We create a Git Strategy and tell Capistrano to use it, our Git Strategy has a simple rule: Don't use git. ####
# module NoGitStrategy
#   def check
#     true
#   end
#
#   def test
#     # Check if the tarball was uploaded.
#     test! " [ -f #{fetch(:project_tarball_path)} ] "
#   end
#
#   def clone
#     true
#   end
#
#   def update
#     true
#   end
#
#   def release
#     # Unpack the tarball uploaded by deploy:upload_tarball task.
#     context.execute "tar -xf #{fetch(:project_tarball_path)} -C #{release_path}"
#     # Remove it just to keep things clean.
#     context.execute :rm, fetch(:project_tarball_path)
#   end
#
#   def fetch_revision
#     # Return the tarball release id, we are using the git hash of HEAD.
#     fetch(:project_release_id)
#   end
# end
########

# Capistrano will use the module in :git_strategy property to know what to do on some Capistrano operations.
#set :git_strategy, NoGitStrategy

# Finally we need a task to create the tarball and upload it,
namespace :deploy do
  desc 'Create and upload project tarball'
  task :upload_tarball do |task, args|
  puts "----> Create a local copy of git repo according to passed branch"
    local_tarball_path = "/tmp/#{fetch(:release_timestamp)}/#{fetch(:application)}-#{fetch(:project_release_id)}.tar.gz"
    tarball_path = fetch(:project_tarball_path)
    git_branch = fetch(:branch)
    # This will create a project tarball from HEAD, stashed and not committed changes wont be released.
 #  `git archive -o #{tarball_path} HEAD`
    run_locally do
      execute "git clone -q -b #{git_branch} file:///#{deploy_to} /tmp/#{fetch(:release_timestamp)}/#{fetch(:application)} && cd /tmp/#{fetch(:release_timestamp)}/#{fetch(:application)} && git checkout -q -b deploy #{fetch(:project_revision_git)}"
      execute "cd /tmp/#{fetch(:release_timestamp)}/#{fetch(:application)} && curl -sS https://getcomposer.org/installer | php"
      execute "cd /tmp/#{fetch(:release_timestamp)}/#{fetch(:application)} && SYMFONY_ENV=prod php composer.phar install --verbose --prefer-dist --optimize-autoloader --no-progress --no-scripts"
      execute "tar -zcvf /tmp/#{fetch(:release_timestamp)}/#{fetch(:application)}-#{fetch(:project_release_id)}.tar.gz -C /tmp/#{fetch(:release_timestamp)}/#{fetch(:application)} ."
      raise 'Error creating tarball.'if $? != 0
    end
    on roles(:all) do
      upload! local_tarball_path, tarball_path
    end
    run_locally do
      execute "sudo rm -rf /tmp/#{fetch(:release_timestamp)}"
    end
  end
  task :media_dir_creation do
  on roles(:all) do
    puts "----> Creating media folder for SonataMedia"
       execute "mkdir -p #{shared_path}/web/uploads/media"
    end
  end
end

#### Run migrations after code is deployed (but not switched yet) ####
desc "----> launch DOC Migr"
namespace :symfony do
  task :migrate do
      on roles(:all) do
      puts "----> Launch DoctrineMigration to update DB structure"
      invoke 'symfony:console', 'doctrine:migrations:migrate', '--no-interaction'
    end
  end
end

#### RELOADING PHP5-FPM SERVICE TO UPDATE WEB "SERVER CACHE" (@Todo or nginx) ####
desc "----> Reloading PHP-FPM service"
namespace :server_config do
 task :reload_php_fpm do
    on roles(:all) do
      puts "----> Reloading PHP-FPM service to reset cache"
      execute "sudo /usr/sbin/service php5-fpm reload"
      #the config file can be used by an attacker to know we use symfony
      execute "/usr/bin/env rm #{deploy_to}/current/web/config.php"
   end
 end
end

#### ACTIONS WORKFLOW ####

#Create good parameters.yml.dist before creating a local "release" thanks to git archive
after "deploy:check", "set_password"

# Attach our upload_tarball task to our custom Capistrano GIT task chain before.
#(=upload local git archive to remote host) before (create_release) extract it on remote
before "git:create_release", "deploy:upload_tarball"

#After remote has extracted received git archive, upload locally generated parameters.yml to remote
before "deploy:updated", "upload_parameters"

before "deploy:updated", "deploy:set_permissions:acl"
after "upload_parameters", "deploy:media_dir_creation"
after 'deploy:updated', 'symfony:assets:install'
after 'deploy:updated', 'bower:install'
after 'deploy:updated', 'symfony:assetic:dump'

after 'deploy:finishing', 'deploy:cleanup'

after "deploy:finished", "server_config:reload_php_fpm"
before "deploy:published", "symfony:migrate" #"deploy:published

#### CAPISTRANO WORKFLOW ####
# deploy
# |__ deploy:starting
# |   |__ [before]
# |   |   |__ deploy:ensure_stage
# |   |   |__ deploy:set_shared_assets
# |   |__ deploy:check
# |__ deploy:started
# |__ deploy:updating
# |   |__ git:create_release
# |   |__ deploy:symlink:shared
# |   |__ symfony:create_cache_dir
# |   |__ symfony:set_permissions
# |__ deploy:updated
# |   |__ symfony:cache:warmup
# |   |__ symfony:clear_controllers
# |__ deploy:publishing
# |   |__ deploy:symlink:release
# |   |__ deploy:restart
# |__ deploy:published
# |__ deploy:finishing
# |   |__ deploy:cleanup
# |__ deploy:finished
#     |__ deploy:log_revision
