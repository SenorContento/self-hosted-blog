<?php
  $mainPage = new cryptography();

  $mainPage->readParameters();

  /* Encryption Method
   *
   * https://raymii.org/s/tutorials/Encrypt_and_decrypt_files_to_public_keys_via_the_OpenSSL_Command_Line.html - Not Asymmetric, but at least Non-Random
   * https://stackoverflow.com/a/7423796/6828099 - Default Cipher - DES-EDE3-CBC
   *
   * openssl enc -des-ede3-cfb -nosalt -in hello -out hello.enc -pass file:key.bin
   *
   * http://php.net/manual/en/function.openssl-encrypt.php
   * 
   * Because this is a symmetric key and not asymmetric, use the same command to decrypt the data as used to encrypt it. Just swap the filenames.
   * I was hoping to have a working implementation of GPG Asymmetric keypair generation while using reproducible, non-random entropy.
   * I either found no implementation, the implementation was in C# (and not worth trying to cross-compile to my RPi for a class assignment),
   * or the implementation was broken. I also do not know enough about cryptography to build my own PGP solution.
   *
   * I hear that I can feed /dev/urandom or /dev/random by piping data into it. OpenSSH uses /dev/urandom.
   * I just don't want to introduce uncontrolled variables that can affect my experiment. These pools already generate
   * their own entropy that isn't from the binaries I retrieve from hotbits.
   *
   * I don't know what generator GPG uses, but using strace, I found out about "~/.gnupg/random_seed".
   * https://www.gnupg.org/documentation/manuals/gnupg/GPG-Configuration.html
   */

  /* Potential Alternate Generators
   *
   * https://serverfault.com/questions/707859/generate-entropy-for-pgp-key
   * https://crypto.stackexchange.com/a/26477/39179
   * https://davesteele.github.io/gpg/2014/09/20/anatomy-of-a-gpg-key/
   *
   * haveged
   * rng-tools
   *
   * /dev/random
   * /dev/urandom
   */

  /* Potential Entropy Fakers
   *
   * phidelius - https://dankaminsky.com/2012/01/03/phidelius/ - http://s3.amazonaws.com/dmk/phidelius-1.01.tgz - PRNG is not seeded
   * keygen - https://ritter.vg/blog-non_persistent_pgp.html - https://ritter.vg/resources/non-persistent-gpg-keys.tgz - MPI subsystem not initialized
   *
   * Both of these could allow replacing the pseudorandom generator with data from Hotbits (for some cryptographic strength testing without introducing uncontrolled variables).
   * Problem is they both are broken for the reasons tacked on to the end of each list item. For example PRNG is not seeded was outputted by ssh-keygen probably because data is
   * not being piped into /dev/urandom. SSH-Keygen grabs data from that device (as monitored by strace on my RPi). MPI subsystem... is outputted by keygen itself.
   *
   * https://www.ibm.com/developerworks/community/blogs/cgaix/entry/fatal_prng_is_not_seeded?lang=en - Potential Solution for PRNG is not seeded
   * phidelius has the most chance of working. It is possible that phidelius is not generating the devices when it executes the keygen programs.
   */

  /* Debug Trace - phidelius
   *
   * ➜  phidelius-1.01 ./phidelius -p "ax-op-nm-qw-yi" -d -e "ssh-keygen -f id_testme"
   * open64 /dev/null
   * poll
   * PRNG is not seeded
   *
   * ----------------------------------------------------------------------------------------------------------------------------------------------------------------
   * I now use https://github.com/robbyrussell/oh-my-zsh for my shell, so it is going to look different
   * from a typical bash shell (as it is not bash and is a modified zsh shell)
   */

  class cryptography {
    public function readParameters() {
      //
    }
  }
?>