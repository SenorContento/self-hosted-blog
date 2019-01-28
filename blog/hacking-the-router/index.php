<?php
  function customPageHeader() {
    print('<link rel="stylesheet" href="/css/blog.css">');
  }

  $loadPage = new loadPage();
  $mainPage = new mainPage();

  $loadPage->loadHeader();
  $mainPage->mainBody();
  $loadPage->loadFooter();

  class loadPage {
    public function loadHeader() {
      $PageTitle="Hacking My Router";
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php");
    }

    public function loadFooter() {
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php");
    }
  }

  class mainPage {
    public function mainBody() {
      print('<div class="blog-post">');

      print('<h1>Hacking a Sagemcom F@ST1704N Router</h1>');

      print('<p>I decided to create this blog because I spent a good five days working on uploading custom firmware to an old router I had and also creating my own programs for it.' . ' ' .
            'The router I have is a <a href="http://www.sagemcom.com/broadband/gateways/dsl-gateways/fst-1704n-2704n/">Sagemcom F@ST1704N</a> which contained a tiny system built with an unknown version of <a href="https://www.uclibc.org/">uClibc</a> (the version was unknown as the executable called it version 0) and a tiny <a href="https://busybox.net/">BusyBox</a>.</p>');

      print('<p>At first, I tried to figure out how to find or compile a version of dd to be loaded onto the router so I can image it to my hard drive. I figured out that I could just use cat piped into netcat so I could copy binaries between my laptop and the router.' . ' ' .
            'Using dd was not longer necessary because I could use cat to copy files, but, I decided that I should still try to create binaries before trying to replace the system so I can learn more about the processor.' . ' ' .
            'I copied the binaries over an ethernet cable that was plugged directly into my laptop to figure out what format the programs were in. They were ELF programs which were compiled for a <a href="https://en.wikipedia.org/wiki/MIPS_architecture#MIPS32/MIPS64_Release_1">MIPS32 Version 1 Processor</a>.</p>');

      print('<p>I couldn\'t figure out how to compile binaries for the stock firmware even despite <a href="https://pastebin.com/3pbiqX5C">asking for help</a>, but I did search for the root filesystem and I found a device block that is 4,874,240 bytes big (4.9 MB).' . ' ' .
            'Checking /dev/root on OpenWRT tells me the root filesystem on the router has 6948352 bytes (6.9 MB). Checking /dev/mtdblock3 leads me to believe there is an additional 4849664 bytes (4.8 MB) on the router.' . ' ' .
            'I strongly suspect I now have the image for the original firmware of the router, but I haven\'t been able to figure out how to decompress it or boot it in QEMU. Running the strings command on the binary doesn\'t reveal anything that proves it\'s a filesystem and I don\'t see a recognizable filesystem when viewing it under a hex editor.' . ' ' .
            'I also tried the file command and partition programs, but none of them revealed anything useful to me. It could be a special squashfs image, but I currently don\'t know what to do with it to find out. I am planning on talking to a professor to see if he can help.</p>');

      print('<p>While compiling a program for the original firmware and also extracting the original firmware failed, but I did find out that the Sagemcom F@ST2704N hardware was close enough that I could flash an image from this <a href="https://webcache.googleusercontent.com/search?q=cache:SowpqcVxHOYJ:https://openwrt.org/toh/plusnet/fast2704nv1%20&cd=1&hl=en&ct=clnk&gl=us">OpenWRT.org page</a>.' . ' ' .
            'I flashed the <a href="https://drive.google.com/open?id=0B4-Ln6UubyEeSjgtVFlaRnpLcHM">Google Drive image with Luci installed</a> and I was able to get web access. The original firmware hosted at <a href="http://192.168.254.254/">192.168.254.254</a> and the OpenWRT firmware hosted at <a href="http://192.168.0.1/">192.168.0.1</a>.' . ' ' .
            'I did some testing and found out that pressing the reset button removes the webserver and activates telnet. I believe this is because the router is split into jffs2 and squashfs. I believe that pressing the reset button deletes the jffs2 partition and leaves the squashfs system intact. You can see more details by navigating to /rom/rom/ and reading the note left in that directory.</p>');

      print('<p>While I do have a new system, I still have not managed to figure out how to compile programs for the router. The problem compiling a functional program leads to this <a href="https://unix.stackexchange.com/questions/496003/how-to-compile-against-uclib-interpreter-for-mips-gcc">StackExchange question</a>.' . ' ' .
            'I managed to figure out the solution to cross-compiling for OpenWRT. I found <a href="https://openwrt.org/docs/guide-developer/helloworld/start">this guide on OpenWRT.org</a> which helped me learn how to install buildtools and I just modified the instructions to fit my version of OpenWRT.' . ' ' .
            'I checked out git commit <a href="https://git.openwrt.org/?p=openwrt/openwrt.git;a=commit;h=70255e3d624cd393612069aae0a859d1acbbeeae">70255e3d624cd393612069aae0a859d1acbbeeae (tag: v18.06.1)</a> and built it for system "Broadcom BCM63xx", subtarget "generic", and profile "Sagem F@ST2704N".</p>');

      print('<p>I executed mips-openwrt-linux-gcc found in git_repo/staging_dir/toolchain-mips_mips32_gcc-7.3.0_musl/bin/. I used the command "mips-openwrt-linux-gcc hello.c -o hello" to test the compiling against a hello world program.' . ' ' .
            'I was able to copy the program over to the router and execute it successfully. Later, for a real test, I decided to try compiling BusyBox (even though the router already had it installed). I followed the instructions under <a href="https://busybox.net/FAQ.html#getting_started">"Building Busybox from Source"</a>.' . ' ' .
            'I had to build BusyBox statically because the dynamic one could not find utmpxname. Since I wasn\'t installing BusyBox to actually use and only to test cross-compiling, I didn\'t bother fixing the missing file so BusyBox could be dynamic.');

      print('<p>I have successfully put a custom program on the router with a custom system, so now I just have to decide what I want to do with the router. I am thinking about using it to forward the phone line over ethernet to one of my Raspberry Pi\'s so I can get virtual fax and phone.' . ' ' .
            'I also may hook the router up to a powerbank and use it as a portable network device. What\'s better than a hotspot, a full fledged router on a battery.</p>');

      print('</div>');
    }
  }
?>