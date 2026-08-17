document.addEventListener("DOMContentLoaded", function () {

    const videoCards = document.querySelectorAll(".video-card");

    const modal = document.getElementById("videoModal");

    const previewVideo =
        document.getElementById("previewVideo");

    const closeVideo =
        document.getElementById("closeVideo");


    /*
    ========================================
    LOAD HLS VIDEO
    ========================================
    */

    function loadHLS(video, videoURL) {

        /*
        Chrome / Edge / Firefox
        */

        if (Hls.isSupported()) {

            const hls = new Hls();

            hls.loadSource(videoURL);

            hls.attachMedia(video);

            /*
            Video ready হলে autoplay
            */

            hls.on(Hls.Events.MANIFEST_PARSED, function () {

                video.play().catch(function (error) {

                    console.log(
                        "Autoplay blocked:",
                        error
                    );

                });

            });


            /*
            HLS error handling
            */

            hls.on(Hls.Events.ERROR, function (
                event,
                data
            ) {

                console.log(
                    "HLS Error:",
                    data
                );

            });


            /*
            HLS instance video-এর সাথে
            store করে রাখছি
            */

            video.hlsInstance = hls;

        }

        /*
        Safari / iPhone / iPad
        */

        else if (
            video.canPlayType(
                "application/vnd.apple.mpegurl"
            )
        ) {

            video.src = videoURL;

            video.addEventListener(
                "loadedmetadata",
                function () {

                    video.play().catch(
                        function (error) {

                            console.log(
                                "Autoplay blocked:",
                                error
                            );

                        }
                    );

                },
                { once: true }
            );

        }

        else {

            console.log(
                "HLS is not supported in this browser."
            );

        }

    }



    /*
    ========================================
    INITIALIZE ALL CARD VIDEOS
    ========================================
    */

    videoCards.forEach(function (card) {

        const video =
            card.querySelector("video");

        const videoURL =
            video.getAttribute("data-video");


        video.muted = true;

        video.loop = true;

        video.playsInline = true;


        loadHLS(
            video,
            videoURL
        );


        /*
        ====================================
        CLICK VIDEO CARD
        ====================================
        */

        card.addEventListener(
            "click",
            function () {

                openVideoPreview(
                    videoURL
                );

            }
        );

    });



    /*
    ========================================
    OPEN VIDEO PREVIEW
    ========================================
    */

    function openVideoPreview(videoURL) {

        modal.classList.add("active");


        /*
        Preview video reset
        */

        previewVideo.pause();

        previewVideo.removeAttribute("src");

        previewVideo.load();


        previewVideo.loop = true;

        previewVideo.muted = false;

        previewVideo.playsInline = true;


        /*
        Load HLS
        */

        if (Hls.isSupported()) {

            const previewHls =
                new Hls();

            previewHls.loadSource(
                videoURL
            );

            previewHls.attachMedia(
                previewVideo
            );


            previewHls.on(
                Hls.Events.MANIFEST_PARSED,
                function () {

                    previewVideo.play().catch(
                        function (error) {

                            console.log(
                                "Preview autoplay error:",
                                error
                            );

                        }
                    );

                }
            );


            /*
            Store HLS instance
            */

            previewVideo.hlsInstance =
                previewHls;

        }

        /*
        Safari
        */

        else if (
            previewVideo.canPlayType(
                "application/vnd.apple.mpegurl"
            )
        ) {

            previewVideo.src =
                videoURL;

            previewVideo.addEventListener(
                "loadedmetadata",
                function () {

                    previewVideo.play();

                },
                { once: true }
            );

        }

    }



    /*
    ========================================
    CLOSE VIDEO
    ========================================
    */

    function closePreview() {

        previewVideo.pause();


        /*
        Destroy HLS instance
        */

        if (
            previewVideo.hlsInstance
        ) {

            previewVideo.hlsInstance.destroy();

            previewVideo.hlsInstance =
                null;

        }


        previewVideo.removeAttribute(
            "src"
        );

        previewVideo.load();


        modal.classList.remove(
            "active"
        );

    }



    /*
    CLOSE BUTTON
    */

    closeVideo.addEventListener(
        "click",
        function () {

            closePreview();

        }
    );



    /*
    ========================================
    CLICK OUTSIDE MODAL
    ========================================
    */

    modal.addEventListener(
        "click",
        function (event) {

            if (
                event.target === modal
            ) {

                closePreview();

            }

        }
    );



    /*
    ========================================
    ESC KEY
    ========================================
    */

    document.addEventListener(
        "keydown",
        function (event) {

            if (
                event.key === "Escape"
            ) {

                if (
                    modal.classList.contains(
                        "active"
                    )
                ) {

                    closePreview();

                }

            }

        }
    ); 

});