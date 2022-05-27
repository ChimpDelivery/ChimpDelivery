# ruby CreateAppstoreApplication.rb user-email user-password com.Talus.ExampleBundleId ExampleBundleName ExampleAppName ExampleCompanyName

require "spaceship"

def set_response(status_code: 200, detail: "no_detail")
    response = { status: status_code, message: detail }
    puts response.to_json
end

if ARGV.length != 6
  puts "Expected 6 arguments: {appstore_acc} {appstore_pass} {bundle_id} {bundle_name} {app_name} {company_name}"
  exit
end

app_company = ARGV[5]
app_platforms = ["IOS"]
app_language = "en-US"
app_version = "1.0"

#
Spaceship::Portal.login(ARGV[0], ARGV[1])
Spaceship::Tunes.login(ARGV[0], ARGV[1])

# create bundle_id
begin
    bundle_id = Spaceship.app.create!(bundle_id: ARGV[2], name: ARGV[3])
rescue Spaceship::UnexpectedResponse
    set_response(status_code: 505, detail: "bundle couldn't created!")
else
    set_response(status_code: 200, detail: "bundle created!")
end

# create app on appstore connect
begin
    app = Spaceship::ConnectAPI::App.create(name: ARGV[4],
                                            version_string: app_version,
                                            sku: ARGV[2],
                                            primary_locale: app_language,
                                            bundle_id: ARGV[2],
                                            platforms: app_platforms,
                                            company_name: app_company)
rescue Spaceship::UnexpectedResponse
    set_response(status_code: 505, detail: "app couldn't created!")
else
    set_response(status_code: 200, detail: "app created!")
end
