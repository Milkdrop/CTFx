var alphabet = "!#$^&*+=0123456789";

function Chaffle (element) {
    var data = {};

    this.element = element;
    this.text = element.textContent;
    this.substitution = "";
    this.isShuffling = false;
    this.speed = 15;
    this.delay = 60;
    this.shuffleProps = [];
    this.reinstateProps = []
}

Chaffle.prototype = {
    constructor: Chaffle,
    init: function() {
        var self = this;
        if (self.isShuffling) return;
        self.clearShuffleTimer();
        self.clearReinstateTimer();
        self.isShuffling = true;
        self.state = 0;
        self.counter = 0;
        self.substitution = "";
        self.shuffleProps = [];
        self.reinstateProps = [];
        var shuffleTimer = setInterval(function() {
            self.shuffle()
        }, self.speed);
        var reinstateTimer = setInterval(function() {
            self.reinstate()
        }, self.delay);
        self.shuffleProps = shuffleTimer;
        self.reinstateProps = reinstateTimer
    },
    shuffle: function() {
        this.element.textContent = this.substitution;
        var textLength = this.text.length;
        var substitutionLength = this.substitution.length;
        if (textLength - substitutionLength > 0) {
            for (var i = 0; i <= textLength - substitutionLength - this.counter; i++) {
                this.element.textContent = this.element.textContent + this.randomStr()
            }
        } else {
            this.clearShuffleTimer()
        }
    },
    reinstate: function() {
        if (this.state == 0) { // Shrink encryption size
            if (this.counter < 2) {
                this.counter++;
                return;
            } else
                this.state = 1;
        } else { // Expand the word back
            if (this.counter > 0)
                this.counter--;
        }

        var textLength = this.text.length;
        var substitutionLength = this.substitution.length;

        if (substitutionLength < textLength)
            this.element.textContent = this.substitution = this.text.substr(0, substitutionLength + 1);
        else
            this.clearReinstateTimer();
    },
    clearShuffleTimer: function() {
        this.isShuffling = false;
        return clearInterval(this.shuffleProps);
    },
    clearReinstateTimer: function() {
        return clearInterval(this.reinstateProps);
    },
    randomStr: function() {
        return alphabet.charAt(Math.floor(Math.random() * alphabet.length));
    }
};