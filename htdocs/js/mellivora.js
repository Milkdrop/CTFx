$(document).ready(function() {
    highlightSelectedMenuItem ();
    highlightLoggedOnTeamName ();

    createBackground ();
    enableDotCanvas ();
    typeWriterSFX ();

    addNavbarMouseoverEffects ();
    addButtonMouseoverEffects ();
    addFooterMouseoverEffects ();
    addDropdownMouseoverEffects ();
    addCheckboxClickEffects ();

    initialiseDialogs ();
    initialiseTooltips ();
    initialiseCountdowns ();

    setFormSubmissionBehaviour();
}

function enableDotCanvas () {
    document.getElementById ("dotCanvas").style.opacity = "1";
}

function initialiseDialogs() {
    initialiseLoginDialog();
    showPageLoadModalDialogs();
}

function initialiseLoginDialog() {
    $('#login-dialog').on('shown.bs.modal', function (e) {
        $('#login-dialog').find('input').first().focus();
    });
}

function showPageLoadModalDialogs() {
    $('.modal.on-page-load').modal();
}

function highlightSelectedMenuItem() {
    var path = window.location.pathname;
    var activeMenuItems = document.querySelectorAll('.nav a[href$="' + path + '"]');

    for (var i = 0; i < activeMenuItems.length; i++) {
        if (activeMenuItems[i] && activeMenuItems[i].parentNode) {
            activeMenuItems[i].parentNode.className = 'active';
        }
    }
}

function createBackground () {
    var backgroundDots = document.getElementById ("background-dots");
    var backgroundDotsCount = 15;

    for (var i = 0; i < backgroundDotsCount; i++) {
        var dot = document.createElement("div");
        dot.className = "background-dot";
        if (i == 0)
            dot.innerText = "07";
        else
            dot.innerText = "+";

        dot.style.top = (Math.random () * window.innerHeight) + "px";
        dot.style.left = (Math.random () * window.innerWidth) + "px";
        dot.style.transform = "scale(" + (0.5 + Math.random () * 0.5) + ")";
        backgroundDots.appendChild (dot);
    }
}

function addNavbarMouseoverEffects() {
    var navbarElements = document.getElementsByClassName ("shuffle-text");
    var audio_navbar = document.getElementById ("audio-navbar");
    var audio_navclick = document.getElementById ("audio-navclick");

    for (var i = 0, len = navbarElements.length; i < len; i++) {
        var element = navbarElements[i];

        if (element.parentNode.classList.contains("active") == false) {
            const shuffler = new shuffleText (element);

            element.addEventListener("mouseenter", function () {
                shuffler.init();
                audio_navbar.currentTime = 0;
                audio_navbar.play();
            });

            element.addEventListener("click", function () {
                audio_navclick.play();
            });
        }
    }
}

function addButtonMouseoverEffects() {
    var buttons = [].concat ([].slice.call(document.getElementsByClassName ("btn")), [].slice.call(document.getElementsByClassName ("close")), [].slice.call(document.getElementsByClassName ("category-link")));

    var audio_button_mouseover = document.getElementById ("audio-button-mouseover");
    var audio_button_click = document.getElementById ("audio-button-click");

    var audio_button_small_mouseover = document.getElementById ("audio-button-small-mouseover");
    var audio_button_small_click = document.getElementById ("audio-button-small-click");

    var audio_button_cancel_mouseover = document.getElementById ("audio-button-cancel-mouseover");
    var audio_button_cancel_click = document.getElementById ("audio-button-cancel-click");

    for (var i = 0, len = buttons.length; i < len; i++) {
        if (buttons[i].classList.contains ("btn-default") || buttons[i].classList.contains ("close")) {
            buttons[i].addEventListener("mouseenter", function () {
                audio_button_cancel_mouseover.currentTime = 0;
                audio_button_cancel_mouseover.play();
            });

            buttons[i].addEventListener("click", function () {
                audio_button_cancel_click.currentTime = 0;
                audio_button_cancel_click.play();
            });
        } else if (buttons[i].classList.contains ("btn-xs") || buttons[i].classList.contains ("category-link")) {
            if (!buttons[i].parentNode.classList.contains("active")) {
                buttons[i].addEventListener ("mouseenter", function () {
                    audio_button_small_mouseover.currentTime = 0;
                    audio_button_small_mouseover.play();
                });

                buttons[i].addEventListener ("click", function () {
                    audio_button_small_click.currentTime = 0;
                    audio_button_small_click.play();
                });

                buttons[i].addEventListener ("mouseout", function () {
                    audio_button_small_mouseover.pause();
                });
            }
        } else {
            buttons[i].addEventListener ("mouseenter", function () {
                audio_button_mouseover.currentTime = 0;
                audio_button_mouseover.play();
            });

            buttons[i].addEventListener ("click", function () {
                audio_button_click.currentTime = 0;
                audio_button_click.play();
            });

            buttons[i].addEventListener ("mouseout", function () {
                audio_button_mouseover.pause();
            });
        }
    }
}

function addDropdownMouseoverEffects () {
    var dropdowns = document.getElementsByClassName ("dropdown-menu");
    var audio_dropdown_open = document.getElementById ("audio-dropdown-open");

    for (var i = 0, len = dropdowns.length; i < len; i++) {
        dropdowns[i].addEventListener("click", function () {
            audio_dropdown_open.currentTime = 0;
            audio_dropdown_open.play();
        });
    }
}

function addFooterMouseoverEffects () {
    var footer = document.getElementById ("footer-fade");
    var audio_footer_mouseover = document.getElementById ("audio-footer-mouseover");

    footer.addEventListener ("mouseenter", function () {
        audio_footer_mouseover.currentTime = 0;
        audio_footer_mouseover.play();
    });
}

function addCheckboxClickEffects () {
    var inputs = document.getElementsByTagName ("input");
    var audio_checkbox_click = document.getElementById ("audio-checkbox-click");

    for (var i = 0, len = inputs.length; i < len; i++) {
        if (inputs[i].type == "checkbox") {
            inputs[i].addEventListener("click", function () {
                audio_checkbox_click.currentTime = 0;
                audio_checkbox_click.play();
            });
        }
    }
}

function typeWriterSFX () {
    var typewriter = document.getElementsByClassName('typewriter')[0];
    var audio_typewriter = document.getElementById ("audio-typewriter");

    if (typewriter != undefined) {
        audio_typewriter.play ();
        setTimeout (function () {audio_typewriter.pause ()}, 300 + (1000 / 65) * typewriter.innerText.length);
    }
}

function highlightLoggedOnTeamName() {
    $(".team_" + global_dict["user_id"]).addClass("label label-info");
}

function initialiseCountdowns() {
    var $countdowns = $('[data-countdown]');
    var countdownsOnPage = $('[data-countdown]').length;

    if (countdownsOnPage) {
        setInterval(function() {
            $countdowns.each(function () {
                var $countdown = $(this);
                var availableUntil = $countdown.data('countdown');
                var availableUntilDate = new Date(availableUntil * 1000);
                var secondsLeft = Math.floor((availableUntilDate.getTime() - Date.now()) / 1000);

                var doneMessage = $countdown.attr('data-countdown-done') || 'No time remaining';
                var countdownMessage = secondsLeft <= 0 ? doneMessage : prettyPrintTime(secondsLeft);
                $countdown.text(countdownMessage);
            });

        }, 1000);
    }
}

function initialiseTooltips() {
    $('.has-tooltip').tooltip();
}

/**
 * Disable all buttons on page on form submit
 */
function setFormSubmissionBehaviour() {
    $('form').on('submit', function(e) {
        $('button').prop('disabled', true);
    });
}

function pluralise(number, name) {
    if (!number) {
        return '';
    }

    return number + ' ' + name + (number > 1 ? 's' : '');

}

function prettyPrintTime(seconds) {
    seconds = Math.floor(seconds);

    var minutes = Math.floor(seconds / 60);
    var hours = Math.floor(minutes / 60);
    var days = Math.floor(hours / 24);

    var daysWords = pluralise(days, 'day');
    var hoursWords = pluralise(hours % 24, 'hour');
    var minutesWords = pluralise(minutes % 60, 'minute');
    var secondsWords = pluralise(seconds % 60, 'second');

    var timeParts = [];
    if (daysWords) timeParts.push(daysWords);
    if (hoursWords) timeParts.push(hoursWords);
    if (minutesWords) timeParts.push(minutesWords);
    if (secondsWords) timeParts.push(secondsWords);

    return timeParts.join(', ') + ' remaining';
}

function shuffleText (element) {
    var data = {};

    this.element = element;
    this.text = element.textContent;
    this.substitution = "";
    this.alphabet = "!#$^&*+=0123456789";
    this.isShuffling = false;
    this.speed = 15;
    this.delay = 60;
    this.shuffleProps = [];
    this.reinstateProps = []
}

shuffleText.prototype = {
    constructor: shuffleText,
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
        return this.alphabet.charAt(Math.floor(Math.random() * this.alphabet.length));
    }
};