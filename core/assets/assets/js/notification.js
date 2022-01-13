if(_s('loggedIn')) {
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker
                .register('./sw.js')
                .then(function (swr) {
                    console.log(swr.scope);
                    console.log('Service Worker Registered')
                });
        });
    }
    function askForNotificationPermission() {
        Notification.requestPermission()
            .then(function(status) {
                console.log(status);
            });
    }
    function notifyUser(title,options) {
        new Notification(title,options);
    }
    function displayConfirmNotification() {
        console.log('Confirmed Registered');
    }
    function urlBase64ToUint8Array(base64String) {
        var padding = '='.repeat((4 - base64String.length % 4) % 4);
        var base64 = (base64String + padding)
            .replace(/\-/g, '+')
            .replace(/_/g, '/');

        var rawData = window.atob(base64);
        var outputArray = new Uint8Array(rawData.length);

        for (var i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    }

    var reg;
    navigator.serviceWorker.ready
        .then(function(swreg) {
            reg = swreg;
            //unsubscribeUser(reg);
            return swreg.pushManager.getSubscription();
        })
        .then(function(sub) {
            if (sub === null) {
                // Create a new subscription
                var vapidPublicKey = _s('nPublicKey');
                var convertedVapidPublicKey = urlBase64ToUint8Array(vapidPublicKey);
                return reg.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: convertedVapidPublicKey
                });
            } else {
                return false;
            }
        })
        .then(function(newSub) {
            if(newSub) {
                savePushDetails(newSub);
            }
        })
        .catch(function(err) {
            console.log(err);
        });

    function unsubscribeUser(swRegistration) {
        swRegistration.pushManager.getSubscription()
            .then(function(subscription) {
                if (subscription) {
                    return subscription.unsubscribe();
                }
            })
            .catch(function(error) {
                console.log('Error unsubscribing', error);
            })
            .then(function() {
                console.log('User is unsubscribed.');
            });
    }
    function savePushDetails(details) {

        var data = {
            module: 'notifications',
            method: 'web_push',
            obj: JSON.stringify(details)
        };
        var request = submitRequest(data,'post');
        request.then(function(response){
            console.log(response);
        });
    }
    askForNotificationPermission();
}
