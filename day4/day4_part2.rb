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
mostMinutes = { 'id' => 0, 'minutes' => 0, 'maxMinute' => 0 }
guardAudit.each do |_item|
  if _item['value'].include? 'Guard'
    # New guard just started their shift
    guardId = _item['value'].string_between_markers('#', ' ')
    if guards.any? { |h| h['id'] == guardId }
      # Guard already exists so use existing guard hash
      currGuard = guards.detect { |h| h['id'] == guardId }
      #Make sure they are awake and at the start of their shift
      currGuard['lastSleepMinute'] = 0
      currGuard['asleep'] = false
    else
      # Brand new guard, add a new hash to the guards array
      currGuard = { 'id' => guardId, 'audit' => {}, 'totalMinutesAsleep' => 0, 'maxMinute' => 0, 'maxMinuteAmount' => 0 }
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
    if currGuard['asleep']
      currGuard['lastSleepMinute'] = currMin
    end
    if currGuard['asleep'] == false
      #Guard has woken up, record all of the minutes asleep from the last sleep time to now
      $i = currGuard['lastSleepMinute']
      #puts $i.to_s << ' : ' << currMin.to_s
      while $i < currMin
        # If the guard is asleep add a counter to the minute
        #if currGuard['asleep'] #&& $i == currMin
          currGuard['audit'][$i] = 0 if currGuard['audit'][$i].nil?
          currGuard['audit'][$i] += 1
          currGuard['totalMinutesAsleep'] += 1
        #end
        $i += 1
      end
    end
  end
end

#Get the guard who was asleep the most
guards.each do |_guard|
    if !_guard['audit'].empty?
        _guard['maxMinute'] = _guard['audit'].max_by{ |k,v| v }[0]
        _guard['maxMinuteAmount'] = _guard['audit'].max_by{ |k,v| v }[1]
    end
end

sleepyGuard = guards.max_by{|k| k['maxMinuteAmount'] }
#Its 2am and I need sleep so just read the values from the console and multiply them:
puts sleepyGuard
