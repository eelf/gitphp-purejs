
/*
With period .period calls cb() with data provided in fire()
since fire() called
until cancel() called or cb() returns true
 */
function Context(cb) {
    this.period = 500;
    this.cb = cb;
    //this.active = true;
    this.data = null;
    this.periodical_bound = this.periodical.bind(this);
}

Context.prototype.cancel = function() {
    clearTimeout(this.to);
    //this.active = false;
};

Context.prototype.periodical = function() {
    console.log('Context.prototype.periodical', this.data);
    if (this.cb(this.data)) {
        this.cancel();
    } else {
        this.to = setTimeout(this.periodical_bound, this.period);
    }
};

Context.prototype.fire = function(data) {
    this.data = data;
    this.periodical_bound(true);
};
