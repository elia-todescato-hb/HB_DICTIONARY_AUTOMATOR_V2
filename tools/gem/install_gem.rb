require 'open3'

command = 'gem list -i colorize'
output, status = Open3.capture2(command)

if status.exitstatus == false
  puts "Gem Colorize Not Installed!"
  `sudo gem install colorize`
end