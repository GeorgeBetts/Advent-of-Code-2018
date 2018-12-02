# part two
filepath = 'input.txt'
frequency = 0
# should really be using a set rather than a list but python is all about being lazy so sue me
freqs = []

with open(filepath) as fp:

    lines = fp.readlines()

    while True:

        for line in lines:
            frequency += int(line)
            if frequency in freqs:
                 # found our duplicate
                print(frequency)
                quit()
            else:
                freqs.append(frequency)