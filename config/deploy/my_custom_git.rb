class NoGitStrategy < Capistrano::SCM::Git
  def check_repo_is_reachable #check
    true
  end

  def repo_mirror_exists? #test
    # Check if the tarball was uploaded.
    backend.test " [ -f #{fetch(:project_tarball_path)} ] "
  end

  def clone_repo #clone
    true
  end

  def update_mirror #update
    true
  end

  def archive_to_release_path #release
  puts "----> CUSTOM GIT STRATEGY STRATEGY (for LAN Git Repo)"
    # Unpack the tarball uploaded by deploy:upload_tarball task. ON REMOTE
    backend.execute("tar -xf #{fetch(:project_tarball_path)} -C #{release_path}")
    # Remove it just to keep things clean.
    backend.execute :rm, fetch(:project_tarball_path)
  end

  def fetch_revision
    # Return the tarball release id, we are using the git hash of HEAD.
    fetch(:project_release_id)
  end
end