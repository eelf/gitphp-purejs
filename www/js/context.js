
/*
With period .period calls cb() with data provided in fire()
since fire() called
until cancel() called or cb() returns true
 */
function Waiter(cb) {
    this.period = 500;
    this.cb = cb;
    //this.active = true;
    this.data = null;
    this.periodical_bound = this.periodical.bind(this);
}

Waiter.prototype.cancel = function() {
    clearTimeout(this.to);
    //this.active = false;
};

Waiter.prototype.periodical = function() {
    console.log('Waiter.prototype.periodical', this.data);
    if (this.cb(this.data)) {
        this.cancel();
    } else {
        this.to = setTimeout(this.periodical_bound, this.period);
    }
};

Waiter.prototype.fire = function(data) {
    this.data = data;
    this.periodical_bound(true);
};

function NewWaiterElementInnerHtml(id) {
    return new Waiter(
        function(page) {
            var el;
            if (el = document.getElementById(id)) {
                el.innerHTML = page;
                return true;
            }
        }
    )
}
