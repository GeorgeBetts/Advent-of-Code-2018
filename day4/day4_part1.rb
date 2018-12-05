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
    'datestring' => dateAsString,
    'value' => line.delete("\n").gsub('[' << dateAsString << '] ', '')
  )
end
file.close

# Sort the values by their time and loop each value
guardAudit = values.sort_by do |obj|
  obj['datetime']
end
guards = []
currGuard = {}
minStart = 0
minEnd = 59
mostMinutes = { 'id' => 0, 'minutes' => 0 }
guardAudit.each do |_item|
  if _item['value'].include? 'Guard'
    # New guard just started their shift
    guardId = _item['value'].string_between_markers('#', ' ')
    if guards.any? { |h| h['id'] == guardId }
      # Guard already exists so use existing guard hash
      currGuard = guards.detect { |h| h['id'] == guardId }
    else
      # Brand new guard, add a new hash to the guards array
      currGuard = { 'id' => guardId, 'audit' => [], 'totalMinutesAsleep' => 0 }
      guards.push(
        currGuard
      )
    end
  else
    # current guard is still working check if he is still asleep
    currGuard['asleep'] = true if _item['value'].include? 'falls asleep'
    currGuard['asleep'] = false if _item['value'].include? 'wakes up'
    # loop each minute
    $i = 0
    currMin = _item['datestring'].split(//).last(2).join.to_i
    while $i < minEnd
      # If the guard is asleep add a counter to the minute
      if currGuard['asleep'] && $i == currMin
        currGuard['audit'][$i] = 0 if currGuard['audit'][$i].nil?
        currGuard['audit'][$i] += 1
        currGuard['totalMinutesAsleep'] += 1
      end
      $i += 1
    end
  end
end

puts guards
puts mostMinutes
