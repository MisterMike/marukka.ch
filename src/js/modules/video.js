import Player from "@vimeo/player";

var elements;

window.onPlayerReady = function(event) {
    event.target.playVideo();
};

window.onPlayerStateChange = function(event) {};

const playYoutube = (elmnt, videoId) => e => {
    e.preventDefault();
    elmnt.classList.add("playing");

    var options = {
        videoId: videoId,
        playerVars: { autoplay: 1, controls: 1 },
        events: {
            onReady: window.onPlayerReady,
            onStateChange: window.onPlayerStateChange
        }
    };

    var videoElement = elmnt.parentNode.querySelector(".video");
    videoElement.classList.add("playing");
    new YT.Player(videoElement.id, options);
};

const playVimeo = (elmnt, videoId) => e => {
    e.preventDefault();
    elmnt.classList.add("playing");

    var options = {
        id: videoId,
        autoplay: true
    };

    var videoElement = elmnt.parentNode.querySelector(".video");
    videoElement.classList.add("playing");
    new Player(videoElement, options);
};

window.onYouTubeIframeAPIReady = function() {
    elements.forEach(function(elmnt, idx) {
        var img = elmnt.querySelector("img");

        var btn = elmnt.querySelector(".play-button");
        var videoId = elmnt.getAttribute("data-video-id");
        var videoType = elmnt.getAttribute("data-video-type");

        if (videoType === "youtube") {
            img.addEventListener("click", playYoutube(elmnt, videoId));
            btn.addEventListener("click", playYoutube(elmnt, videoId));
        } else {
            img.addEventListener("click", playVimeo(elmnt, videoId));
            btn.addEventListener("click", playVimeo(elmnt, videoId));
        }
    });
};

export default function() {
    // add youtube script
    elements = Array.from(document.querySelectorAll(".video-link"));
    if (elements.length > 0) {
        // load youtube API
        var tag = document.createElement("script");

        tag.src = "https://www.youtube.com/iframe_api";
        var firstScriptTag = document.getElementsByTagName("script")[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
    }
}
