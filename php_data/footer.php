<?php
  $loadFooter = new loadFooter();
  $loadFooter->printStartFooter();
  $loadFooter->printVisibleFooter();
  $loadFooter->printCustomPageFooter();
  $loadFooter->printEndFooter();

  class loadFooter {
    public function printVisibleFooter() {
      print("\n\t\t" . '<br><br><br><br>');
      print("\n\t\t" . '<!-- For JET - I have been trying to figure out how to force the footer at the bottom of the page. I have not succeded yet, so I may ask for help later.-->');
      print("\n\t\t" . '<footer>' .
            "\n\t\t\t" . '<p class="footer-message">' .
            "\n\t\t\t\t" . 'Hello, did you see the fish walking outside? Perhaps you want to <a id="clear-cache" onClick="clearAllCaches();">clear the cache</a> instead?' .
            "\n\t\t\t" . '</p>' .
            "\n\t\t" . '</footer>');
    }

    public function printStartFooter() {
      print("\n\t\t" . '</div>');
    }

    public function printCustomPageFooter() {
      if(function_exists('customPageFooter')) {
        print("\n");
        customPageFooter();
      }
    }

    public function printEndFooter() {
      print("\n\t" . '<script>
      function clearAllCaches() {
        caches.keys().then(function(names) {
          for (let name of names) {
            caches.delete(name);
          }
        });

        // Uninstall and Reinstall service worker to download new cache and new service worker
        if(\'serviceWorker\' in navigator && \'PushManager\' in window) {
          uninstallServiceWorker();
        }

        function uninstallServiceWorker() {
          // Uninstall Service Worker
          navigator.serviceWorker.getRegistrations().then(function(registrations) {
            console.log("Uninstalling Service Worker!");
            for(let registration of registrations) {
              registration.unregister()
            }

            /*
             * I keep call the reinstall function here so I am not running a race condition.
             *
             * If I don\'t, then the registration function wins and the uninstaller will keep
             *   the service worker from installing and activating.
            */

            reinstallServiceWorker();
          });
        }

        function reinstallServiceWorker() {
          // Reinstall Service Worker
          console.log("Will the service worker reinstall?");
          navigator.serviceWorker.register(\'/service-worker.js\').then(function(reg) {
            //reg.update();
            console.log("Yes, it reinstalled.");
          }).catch(function(err) {
            console.log("No, it didn\'t reinstall. This happened: ", err);
          });
        }
      }
      </script>');
      print("\n\t" . '</body>');
      print("\n" . '</html>');
    }
  }
?>