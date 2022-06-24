self.addEventListener('push', function(event) {
    var data = JSON.parse(event.data.text());
    if(data.type === 'newOrder') {
        var title = data.title;
        var options = {
            body: data.message,
            /*icon: 'images/icon.png',
            badge: 'images/badge.png'*/
        };
        event.waitUntil(self.registration.showNotification(title, options));
    }
});
