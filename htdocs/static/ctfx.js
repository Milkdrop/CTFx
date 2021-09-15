function highlight_current_navbar_location() {
    var element = document.querySelector('#navbar-buttons a[href*="/' + window.location.pathname.split("/")[1] + '"]');

    if (element) {
        element.className = "active";
        element.innerHTML = "<b>&gt; </b>" + element.innerHTML;
    }
}

function addLinkMouseoverSFX() {
    for (var t = document.querySelectorAll("#navbar-buttons a,#navbar-buttons button,.challenge-filename"), e = document.getElementById("audio-navbar"), n = document.getElementById("audio-navclick"), o = 0, i = t.length; o < i; o++) {
        var a = t[o];
        if (0 == a.classList.contains("active")) {
            a.addEventListener("mouseenter", function() {
                e.currentTime = 0, e.play()
            }), a.addEventListener("click", function() {
                n.play()
            })
        }
    }
}

function addButtonMouseoverSFX() {
    for (var t = [].concat([].slice.call(document.getElementsByClassName("btn")), [].slice.call(document.getElementsByClassName("close")), [].slice.call(document.getElementsByClassName("category-link"))), e = document.getElementById("audio-button-mouseover"), n = document.getElementById("audio-button-click"), o = document.getElementById("audio-button-small-mouseover"), i = document.getElementById("audio-button-small-click"), a = document.getElementById("audio-button-cancel-mouseover"), s = document.getElementById("audio-button-cancel-click"), l = 0, r = t.length; l < r; l++) t[l].classList.contains("btn-4") || t[l].classList.contains("close") ? (t[l].addEventListener("mouseenter", function() {
        a.currentTime = 0, a.play()
    }), t[l].addEventListener("click", function() {
        s.currentTime = 0, s.play()
    })) : t[l].classList.contains("btn-xs") || t[l].classList.contains("category-link") ? t[l].parentNode.classList.contains("active") || (t[l].addEventListener("mouseenter", function() {
        o.currentTime = 0, o.play()
    }), t[l].addEventListener("click", function() {
        i.currentTime = 0, i.play()
    }), t[l].addEventListener("mouseout", function() {
        o.pause()
    })) : (t[l].addEventListener("mouseenter", function() {
        e.currentTime = 0, e.play()
    }), t[l].addEventListener("click", function() {
        n.currentTime = 0, n.play()
    }), t[l].addEventListener("mouseout", function() {
        e.pause()
    }))
}

function addDropdownMouseoverEffects() {
    for (var t = document.getElementsByClassName("dropdown-menu"), e = document.getElementById("audio-dropdown-open"), n = 0, o = t.length; n < o; n++) t[n].addEventListener("click", function() {
        e.currentTime = 0, e.play()
    })
}

function addCheckboxClickEffects() {
    for (var t = document.getElementsByTagName("input"), e = document.getElementById("audio-checkbox-click"), n = 0, o = t.length; n < o; n++) "checkbox" == t[n].type && t[n].addEventListener("click", function() {
        e.currentTime = 0, e.play()
    })
}

function typeWriterSFX() {
    var t = document.getElementsByClassName("typewriter")[0],
        e = document.getElementById("audio-typewriter");
    null != t && (e.play(), setTimeout(function() {
        e.pause()
    }, 300 + 1e3 / 65 * t.innerText.length))
}

function highlightLoggedOnTeamName() {
    var t = document.getElementsByClassName("team_" + global_dict.user_id)[0];
    null != t && (t.classList.add("our-team"))
}

function init_countdowns() {
    var countdowns = document.querySelectorAll(".countdown");
    console.log(countdowns);
    
    setInterval(function() {
        for (countdown of countdowns) {
            var new_time = parseInt(countdown.attributes["time-difference"].value);
            if (new_time != 0) new_time -= 1;

            var approx_fun = (new_time > 0) ? Math.floor : Math.ceil;
            
            var seconds = Math.abs(new_time % 60);
            var minutes = Math.abs(approx_fun(new_time / 60) % 60);
            var hours = Math.abs(approx_fun(new_time / (60 * 60)) % 24);
            var days = Math.abs(approx_fun(new_time / (60 * 60 * 24)));
            
            var new_inner_text = "";
            if (days) new_inner_text = days + " Day" + (days==1?"":"s") + ", " + hours + " Hour" + (hours==1?"":"s");
            else if (hours) new_inner_text = hours + " Hour" + (hours==1?"":"s") + ", " + minutes + " Minute" + (minutes==1?"":"s");
            else if (minutes) new_inner_text = minutes + " Minute" + (minutes==1?"":"s") + ", " + seconds + " Second" + (seconds==1?"":"s");
            else new_inner_text = seconds + " Second" + (seconds==1?"":"s");
            
            // Lazy update
            if (new_inner_text != countdown.innerText) countdown.innerText = new_inner_text;
            countdown.attributes["time-difference"].value = new_time;
        }
    }, 1000);
}

function setFormSubmissionBehaviour() {
    $("form").on("submit", function(t) {
        $("button").prop("disabled", !0)
    })
}

function ctfx_init() {
    highlight_current_navbar_location();
    init_countdowns();

    highlightLoggedOnTeamName();
    setFormSubmissionBehaviour();
}

function ctfx_assign_sfx() {
    typeWriterSFX();
    addLinkMouseoverSFX();
    addButtonMouseoverSFX();
    addDropdownMouseoverEffects();
    addCheckboxClickEffects();
}
