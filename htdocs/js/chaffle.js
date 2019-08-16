(function(root, factory) {
    if (typeof define === "function" && define.amd) {
        define([], factory)
    } else if (typeof exports === "object") {
        module.exports = factory()
    } else {
        root.Chaffle = factory()
    }
})(this, function() {
    "use strict";

    var alphabet = "~!@#$%^&*()0123456789";

    function extend() {
        var extended = {};
        var deep = false;

        if (Object.prototype.toString.call(arguments[0]) === "[object Boolean]") {
            deep = arguments[0];
            i++
        }

        function merge(obj) {
            for (var prop in obj) {
                if (Object.prototype.hasOwnProperty.call(obj, prop)) {
                    if (deep && Object.prototype.toString.call(obj[prop]) === "[object Object]") {
                        extended[prop] = extend(true, extended[prop], obj[prop])
                    } else {
                        extended[prop] = obj[prop]
                    }
                }
            }
        }
        for (var i = 0; i < arguments.length; i++) {
            var obj = arguments[i];
            merge(obj)
        }
        return extended
    }

    function Chaffle(element, options) {
        var data = {};

        this.options = {
            speed: 20,
            delay: 100
        };

        this.options = extend(this.options, options, data);
        this.element = element;
        this.text = this.element.textContent;
        this.substitution = "";
        this.state = false;
        this.shuffleProps = [];
        this.reinstateProps = []
    }
    
    Chaffle.prototype = {
        constructor: Chaffle,
        init: function() {
            var self = this;
            if (self.state) return;
            self.clearShuffleTimer();
            self.clearReinstateTimer();
            self.state = true;
            self.substitution = "";
            self.shuffleProps = [];
            self.reinstateProps = [];
            var shuffleTimer = setInterval(function() {
                self.shuffle()
            }, self.options.speed);
            var reinstateTimer = setInterval(function() {
                self.reinstate()
            }, self.options.delay);
            self.shuffleProps = shuffleTimer;
            self.reinstateProps = reinstateTimer
        },
        shuffle: function() {
            this.element.textContent = this.substitution;
            var textLength = this.text.length;
            var substitutionLength = this.substitution.length;
            if (textLength - substitutionLength > 0) {
                for (var i = 0; i <= textLength - substitutionLength; i++) {
                    this.element.textContent = this.element.textContent + this.randomStr()
                }
            } else {
                this.clearShuffleTimer()
            }
        },
        reinstate: function() {
            var textLength = this.text.length;
            var substitutionLength = this.substitution.length;
            if (substitutionLength < textLength) {
                this.element.textContent = this.substitution = this.text.substr(0, substitutionLength + 1)
            } else {
                this.clearReinstateTimer()
            }
            this.state = false
        },
        clearShuffleTimer: function() {
            return clearInterval(this.shuffleProps)
        },
        clearReinstateTimer: function() {
            return clearInterval(this.reinstateProps)
        },
        randomStr: function() {
            return alphabet.charAt (Math.floor(Math.random() * alphabet.length));
            //return String.fromCharCode(33 + Math.round(Math.random() * 93));
        }
    };
    return Chaffle
});