set :application, "desBonnesAdresses"
#set :domain,      #{application}.com"
set :domain,      "37.187.47.67"
set :user,        "root"
set :deploy_to,   "/var/www/desBonnesAdresses"
set :app_path,    "app"

set :scm,         :git
set :repository,  "https://github.com/lologhi/#{application}.git"
set :deploy_via,  :remote_cache

set :model_manager, "doctrine"

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server

set :use_composer,      true
set :shared_files,      ["app/config/parameters.yml"]
set :shared_children,   [app_path + "/logs", web_path + "/uploads", "vendor"]

set  :keep_releases,  3
set  :use_sudo,       false

# Be more verbose by uncommenting the following line
# logger.level = Logger::MAX_LEVEL

after "deploy:finalize_update" do
  run "cd #{latest_release} && bower install --allow-root --no-color"
  run "cd #{latest_release} && php app/console assetic:dump --env=prod"
  run "cd #{latest_release}/web && touch lastmodification.txt"
  run "sudo chown -R www-data:www-data #{latest_release}/#{cache_path}"
  run "sudo chown -R www-data:www-data #{latest_release}/#{log_path}"
  run "sudo chmod -R 777 #{latest_release}/#{cache_path}"
  run "sudo mongo --quiet bonnesadresses --eval 'db.Adresse.remove({}) ' >> /tmp/mongo_output.txt"
  run "sudo mongoimport --quiet -d bonnesadresses -c Adresse --file #{latest_release}/StarredPlaces.geojson --jsonArray"
  run "curl http://desbonnesadresses.fr/slugfeeder --output '/tmp/curl_output.txt' --silent"
  run "cd #{latest_release} && php app/console presta:sitemap:dump"
  run "cd #{latest_release}/web && git log -1 --format='%cd' >lastmodification.txt"
end
