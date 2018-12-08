###
### This was a failed attempt - needed to restart the script because I just didn't understand how to sort the steps
### this sorts the steps by the number of prerequistites properly but doesn't take into account alphabetical order
### I could not re-sort alphabetially without blowing out the original order
###

### Define Functions
def getStepOrder(steps, letter, order)
  steps.each do |_step|
    order += 1 if _step['prerequisites'].include? letter
  end
  order
end

def sortSteps(steps)
  steps.each_with_index do |_step, _index|
    _step['prerequisites'].each do |_prerequisiteLetter|
      (_index..steps.length).each do |subindex|
        next unless subindex < steps.length
        next unless steps[subindex]['letter'] == _prerequisiteLetter

        # -1 the order, re-sort the steps array and do this loop again
        _step['order'] += 1
        sortSteps(steps.sort_by { |h| [h['order'], h['alphabetOrder']] })
      end
    end
  end
end

# read file
file = File.open(__dir__ << '/input.txt', 'r')
# create array of steps
steps = []
alphabetOrder = 0
('A'..'Z').each do |letter|
  steps.push('letter' => letter, 'prerequisites' => [], 'order' => 0, 'alphabetOrder' => alphabetOrder)
  alphabetOrder += 1
end
# create an array of each letter with the prerequisites
file.each_line do |line|
  letter = line[36, 1]
  prerequisite = line[5, 1]
  # Have we listed the step for this letter before
  if steps.any? { |h| h['letter'] == letter }
    currStep = steps.detect { |h| h['letter'] == letter }
    currStep['prerequisites'].push(prerequisite)
  end
end
file.close
# loop the steps and set the initial order
steps.each do |_step|
  _step['order'] = getStepOrder(steps, _step['letter'], _step['order'])
end
# Sort steps by descending order
steps = steps.sort_by { |h| [-h['order'], h['alphabetOrder']] }
# Then reset the order values
steps.each do |_step|
  _step['order'] = 0
end
sortSteps(steps)
steps = steps.sort_by { |h| [h['order'], h['alphabetOrder']] }
(0..steps.length).each do |_index|
  if steps[_index]['alphabetOrder'] > steps[_index + 1]['alphabetOrder']
    #Alphabet order is wrong - check if the following letter is a prerequisite
    if
  end
end

puts steps
steps.each do |_step|
  print _step['letter']
end
