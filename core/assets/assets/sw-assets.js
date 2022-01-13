console.log(window);
console.log(document);
self.addEventListener('push', function(event) {
    var data = JSON.parse(event.data.text());
    if(data.type === 'newOrder') {
        //bus.$emit('updateOnlineOrderList',true);
        var title = data.title;
        var options = {
            body: data.message,
            /*icon: 'images/icon.png',
            badge: 'images/badge.png'*/
        };
        event.waitUntil(self.registration.showNotification(title, options));
    }
});
