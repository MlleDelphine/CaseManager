# # config valid only for current version of Capistrano
# lock '3.6.1'
#
# set :application, 'my_app_name'
# set :repo_url, 'git@example.com:me/my_repo.git'
#
# # Default branch is :master
# # ask :branch, `git rev-parse --abbrev-ref HEAD`.chomp
#
# # Default deploy_to directory is /var/www/my_app_name
# # set :deploy_to, '/var/www/my_app_name'
#
# # Default value for :scm is :git
# # set :scm, :git
#
# # Default value for :format is :airbrussh.
# # set :format, :airbrussh
#
# # You can configure the Airbrussh format using :format_options.
# # These are the defaults.
# # set :format_options, command_output: true, log_file: 'log/capistrano.log', color: :auto, truncate: :auto
#
# # Default value for :pty is false
# # set :pty, true
#
# # Default value for :linked_files is []
# # append :linked_files, 'config/database.yml', 'config/secrets.yml'
#
# # Default value for linked_dirs is []
# # append :linked_dirs, 'log', 'tmp/pids', 'tmp/cache', 'tmp/sockets', 'public/system'
#
# # Default value for default_env is {}
# # set :default_env, { path: "/opt/ruby/bin:$PATH" }
#
# # Default value for keep_releases is 5
# # set :keep_releases, 5

#set :stages,        %w(dev qualif preprod prod)
#set :default_stage, "dev"
#set :stage_dir,     "app/config/deploy"

#set :repo_url, 'git@gitlab.fime.dev:web/FSOnline.git'

# Set this to 2 for the old directory structure
set :symfony_directory_structure, 3

# Set this to 4 if using the older SensioDistributionBundle
set :sensio_distribution_version, 5

#require 'capistrano/ext/multistage'

####### CAPISTRANO #######
#
# # Symfony console commands will use this environment for execution
# set :symfony_env,  "prod"
#
# # Set this to 2 for the old directory structure
# set :symfony_directory_structure, 3
# # Set this to 4 if using the older SensioDistributionBundle
# set :sensio_distribution_version, 5
#
# # symfony-standard edition directories
set :app_path, "app"
set :web_path, "web"
set :var_path, "var"
set :bin_path, "bin"

# # The next 3 settings are lazily evaluated from the above values, so take care
# # when modifying them
set :app_config_path, "app/config"
set :log_path, "var/logs"
set :cache_path, "var/cache"
#
# set :symfony_console_flags, "--no-debug"
#
# # Remove app_dev.php during deployment, other files in web/ can be specified here
# set :controllers_to_clear, ["app_*.php"]
#
# # asset management
# set :assets_install_path, "web"
# set :assets_install_flags,  '--symlink'
#
# # Share files/directories between releases
# set :linked_files, [%w{app/config/parameters.yml}]
# set :linked_dirs, ["var/logs"]
#
# # Set correct permissions between releases, this is turned off by default
# set :file_permissions_paths, ["var"]
# set :permission_method, false


set :symfony_console_path, "bin/console"
set :symfony_console_flags, "--env=prod --no-debug"

set :controllers_to_clear, ["app_*.php"]

# asset management
set :assets_install_path, "web"
set :assets_install_flags,  '--symlink'

set :application, 'casemanager'
set :deploy_to, '/var/www/casemanager'
set :ssh_user, 'root' #'u47456639'
#set :repo_url, 'git@gitlab.fime.dev:web/FSOnline.git'

Rake::Task['deploy:updated'].prerequisites.delete('composer:install')