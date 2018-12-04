require 'date'
require 'time'

class String
  def string_between_markers(marker1, marker2)
    self[/#{Regexp.escape(marker1)}(.*?)#{Regexp.escape(marker2)}/m, 1]
  end
end

# read file
file = File.open(__dir__ << '/input.txt', 'r')
# create array of values
values = []
# loop each line in the file to seperate the time so it can be sorted
file.each_line do |line|
  # I seems to have to define this variable here, it errors if I put the Date parsing inside the hash creation?
  dateAsString = line.string_between_markers('[', ']')
  dateTime = Time.strptime(dateAsString, '%Y-%m-%d %H:%M')
  values.push(
    'datetime' => dateTime,
    'value' => line.delete("\n").gsub('[' << dateAsString << '] ', '')
  )
end
file.close

# Sort the values by their time and loop each value
guardAudit = values.sort_by do |obj|
  obj['datetime']
end
guards = []
guardAudit.each do |_item|
  puts "String includes 'Guard'" if 'Guard'.include? 'Guard'
end
