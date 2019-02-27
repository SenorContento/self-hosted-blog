This ZIP file contains (psuedo)-randomly generated data from a Geiger-Müller tube connected
to a Cæsium-137 source. More information can be found on Fourmilab's site. [1]

I created this experiment to measure the randomness of the data produced from Hotbits.
The goal is to produce more secure keys used for encryption.

This experiment requires downloading (psuedo)-randomly generated data from Hotbits. [2].
After downloading the data, the server then encrypts a static control file
(which is provided as original.txt). It then decrypts the file to ensure encryption
functioned successfully.

To avoid uncontrolled variables when encrypting the control file,
I have had to disable using a salt* and I set the encryption cipher to
"des-ede3-cfb". I used this cipher because it is the default used by
openssl. [3][4].

The file "original.txt" is just a control file which never changes and is used to produce
"encrypted.bin" and then subsequently "decrypted.txt". The file, "key.bin", is the raw binary
used to encrypt "original.txt" with the cipher "des-ede3-cfb" and no salt. For personal analysis,
the file "key.json" is provided which gives detailed information as to the nature of the key.
The service, JSON Editor Online [5], can help with interpreting JSON formatted data.

The key has two generators which could have produced it. One is, it could have come straight from
the Cæsium-137 source, and the other is, it could have been produced by a strong algorithm that
was seeded from the Cæsium-137 source. The file "key.json", will specify the generator type as either
"pseudorandom" or as "random". The generator type, "random", is straight from the Cæsium-137 source,
while "pseudorandom" was seeded. More information can be learned from [1] and [6].

Currently, to measure the randomness of the data provided by Hotbits, I use the program called, ent [7],
to test the randomness of the data. I currently do not test the randomness of the encrypted files, however,
that feature is on my todo list. To test the randomness of the data, one could either compile the test program
themselves or send a request to my API [8] with the data '{"analyze": true, "id": integer, "count": true, "format": "csv"}'.
One would replace "integer" with the rowID used to download this zip file. The rowID is also provided in "key.json".

* A salt is an additional piece of data used by encryption algorithms to ensure
a password or key cannot easily be cracked (reversed) by software designed for cracking
passwords from hashes or encrypted files.

Any data released by my server, for the purposes of this experiment, is Public Domain [9]. The server itself is GPL-3 [10].

[1]: https://www.fourmilab.ch/hotbits/hardware3.html
[2]: https://www.fourmilab.ch/hotbits/
[3]: https://stackoverflow.com/a/7423796/6828099
[4]: https://github.com/openssl/openssl/blob/master/apps/req.c#L244
[5]: https://jsoneditoronline.org/
[6]: https://www.fourmilab.ch/hotbits/source/hotbits-c3.html
[7]: https://www.fourmilab.ch/random/random.zip
[8]: https://web.senorcontento.com/api/hotbits
[9]: https://creativecommons.org/share-your-work/public-domain/cc0/
[10]: https://github.com/bgbrandongomez/self-hosted-blog/blob/master/LICENSE.md