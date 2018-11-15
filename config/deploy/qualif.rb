# server '52.58.9.210', user: fetch(:ssh_user), roles: %w{web app db}
# server '52.58.9.210', user: fetch(:ssh_user), roles: %w{app}
server 'fsonline-qualif.fimeconnect.com', user: fetch(:ssh_user), roles: %w{web app db}
server 'fsonline-qualif.fimeconnect.com', user: fetch(:ssh_user), roles: %w{app}


set :ssh_options, {
  keys: %w(/home/vagrant/.ssh/keypair-fsonline-qualification.pem),
  forward_agent: true,
  auth_methods: %w(publickey password)
}

desc "Apply password for right QUALIF Env"
task :set_password do
  run_locally do
  puts "----> Apply password for QUALIF environnement in parameters_{env}.yml"
  execute "python /var/www/fsonline/app/config/deploy/set_password.py --dir app/config/parameters --file parameters_qualif.yml --key /var/www/fsonline/app/config/deploy/passwords.kdb"
  end
end

desc "Upload passwords_{env}.yml with keepass values"
task :upload_parameters do
  on roles(:all) do
  puts "----> Upload passwords_{env}.yml with keepass values"
   origin_file = "app/config/parameters/parameters_qualif.yml"
   destination_file = "#{shared_path}/app/config/parameters.yml"
     execute "mkdir -p #{File.dirname(destination_file)}"
     upload! origin_file, destination_file
  end
end

load 'config/deploy/_common.rb'

