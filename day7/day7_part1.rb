### Define Functions
# read file
file = File.open(__dir__ << '/input.txt', 'r')
# create array of steps
steps = []
alphabetOrder = 0
('A'..'Z').each do |letter|
  steps.push('letter' => letter, 'prerequisites' => [], 'alphabetOrder' => alphabetOrder)
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

# create a step order string
stepOrder = ''
while stepOrder.length != 26
  # get the first step which doesn't have any prerequisites (sorted alphabeitcally)
  blankPrerequisites = steps.select { |step| step['prerequisites'].empty? }
  nextStep = blankPrerequisites.min_by { |h| h['alphabetOrder'] }
  # add this next step to the steporder string
  stepOrder += nextStep['letter']
  # now that this letter is done remove any dependencies for this letter
  steps.each do |_step|
    if _step['prerequisites'].include? nextStep['letter']
      _step['prerequisites'].delete(nextStep['letter'])
    end
  end
  # remove this letter from the steps
  stepOrder.each_char do |c|
    steps.delete_if { |h| h['letter'] == c }
  end
end

puts stepOrder
