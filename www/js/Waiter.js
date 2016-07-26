
/*
With period .period calls cb() with data provided in fire()
since fire() called
until cancel() called or cb() returns true
 */
function Waiter(cb) {
    this.period = 500;
    this.cb = cb;
    this.data = null;
    this.periodical_bound = this.periodical.bind(this);
}

Waiter.prototype.cancel = function() {
    clearTimeout(this.to);
};

Waiter.prototype.periodical = function() {
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

Waiter.NewElementInnerHtml = function(id) {
    return new Waiter(
        function(page) {
            var el;
            if (el = document.getElementById(id)) {
                el.innerHTML = page;
                return true;
            }
        }
    )
};
