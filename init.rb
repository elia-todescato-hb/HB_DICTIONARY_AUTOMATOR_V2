require_relative "./tools/gem/install_gem.rb"
require 'colorize'
require 'open3'

# Esegui il comando e ottieni l'output e il valore di ritorno
puts "Executing command: php -S localhost:8001 -t ./".blue
command = 'php -S localhost:8001 -t ./'
output, status = Open3.capture2(command)


if status.exitstatus == 1
  puts "ports already in use would you like another ports? [y/n] ".yellow
  option = gets

  if option.chomp.upcase =='Y'
    puts "trying again"

    (8001..8100).each do |port|
      command = "php -S localhost:#{port} -t ./"
      output, status = Open3.capture2(command)
      if status.exitstatus == 1
        puts "port #{port} is in use".yellow
      end
    end
  end
end

