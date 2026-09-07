document.addEventListener("DOMContentLoaded", function () {

    const videoCards = document.querySelectorAll(".video-card");

    const modal = document.getElementById("videoModal");
    const previewVideo = document.getElementById("previewVideo");
    const closeVideo = document.getElementById("closeVideo");


    /*
    ========================================
    CHECK HLS URL
    ========================================
    */

    function isHLS(videoURL) {

        if (!videoURL) {
            return false;
        }

        return /\.m3u8(?:\?|#|$)/i.test(videoURL);
    }


    /*
    ========================================
    DESTROY HLS INSTANCE
    ========================================
    */

    function destroyHLS(video) {

        if (video && video.hlsInstance) {

            video.hlsInstance.destroy();

            video.hlsInstance = null;
        }
    }


    /*
    ========================================
    LOAD NORMAL MP4 VIDEO
    ========================================
    */

    function loadNormalVideo(video, videoURL) {

        if (!video || !videoURL) {
            return;
        }

        destroyHLS(video);

        video.pause();

        video.removeAttribute("src");

        video.load();


        video.src = videoURL;

        video.muted = true;
        video.loop = true;
        video.playsInline = true;


        /*
        Video load হলে play করার চেষ্টা
        */

        video.addEventListener(
            "loadeddata",
            function () {

                video.play().catch(function (error) {

                    console.log(
                        "Normal video autoplay blocked:",
                        error
                    );

                });

            },
            { once: true }
        );


        /*
        Video error
        */

        video.addEventListener(
            "error",
            function () {

                console.log(
                    "Video loading error:",
                    videoURL,
                    video.error
                );

            },
            { once: true }
        );


        video.load();
    }


    /*
    ========================================
    LOAD HLS VIDEO
    ========================================
    */

    function loadHLS(video, videoURL) {

        if (!video || !videoURL) {
            return;
        }


        /*
        আগে পুরোনো HLS destroy
        */

        destroyHLS(video);


        /*
        ====================================
        Chrome / Edge / Firefox
        ====================================
        */

        if (
            typeof Hls !== "undefined" &&
            Hls.isSupported()
        ) {

            const hls = new Hls({

                enableWorker: true,

                lowLatencyMode: false

            });


            hls.loadSource(videoURL);

            hls.attachMedia(video);


            /*
            HLS instance store
            */

            video.hlsInstance = hls;


            /*
            Manifest ready
            */

            hls.on(
                Hls.Events.MANIFEST_PARSED,
                function () {

                    video.play().catch(function (error) {

                        console.log(
                            "HLS autoplay blocked:",
                            error
                        );

                    });

                }
            );


            /*
            HLS error
            */

            hls.on(
                Hls.Events.ERROR,
                function (event, data) {

                    console.log(
                        "HLS Error:",
                        data
                    );


                    /*
                    Fatal error হলে recover করার চেষ্টা
                    */

                    if (data.fatal) {

                        switch (data.type) {

                            case Hls.ErrorTypes.NETWORK_ERROR:

                                console.log(
                                    "HLS network error. Restarting..."
                                );

                                hls.startLoad();

                                break;


                            case Hls.ErrorTypes.MEDIA_ERROR:

                                console.log(
                                    "HLS media error. Recovering..."
                                );

                                hls.recoverMediaError();

                                break;


                            default:

                                console.log(
                                    "Fatal HLS error."
                                );

                                hls.destroy();

                                video.hlsInstance = null;

                                break;
                        }

                    }

                }
            );

        }


        /*
        ====================================
        Safari / iPhone / iPad
        ====================================
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

                    video.play().catch(function (error) {

                        console.log(
                            "Safari HLS autoplay blocked:",
                            error
                        );

                    });

                },
                { once: true }
            );

        }


        /*
        ====================================
        HLS Not Supported
        ====================================
        */

        else {

            console.log(
                "HLS is not supported in this browser:",
                videoURL
            );

        }

    }


    /*
    ========================================
    LOAD VIDEO AUTOMATICALLY
    ========================================
    */

    function loadVideo(video, videoURL) {

        if (!video || !videoURL) {

            console.log(
                "Video URL not found."
            );

            return;
        }


        /*
        Common settings
        */

        video.muted = true;

        video.loop = true;

        video.playsInline = true;


        /*
        HLS হলে HLS player
        */

        if (isHLS(videoURL)) {

            loadHLS(
                video,
                videoURL
            );

        }


        /*
        MP4 / normal video হলে
        */

        else {

            loadNormalVideo(
                video,
                videoURL
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


        if (!video) {
            return;
        }


        const videoURL =
            video.getAttribute("data-video");


        if (!videoURL) {

            console.log(
                "Video URL missing from card."
            );

            return;
        }


        /*
        Load video
        */

        loadVideo(
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

        if (!modal || !previewVideo) {
            return;
        }


        if (!videoURL) {
            return;
        }


        /*
        Open modal
        */

        modal.classList.add("active");


        /*
        Stop previous video
        */

        previewVideo.pause();


        /*
        Destroy previous HLS
        */

        destroyHLS(
            previewVideo
        );


        /*
        Reset video
        */

        previewVideo.removeAttribute("src");

        previewVideo.load();


        previewVideo.loop = true;

        previewVideo.muted = false;

        previewVideo.playsInline = true;


        /*
        ====================================
        HLS PREVIEW
        ====================================
        */

        if (isHLS(videoURL)) {


            /*
            Chrome / Edge / Firefox
            */

            if (
                typeof Hls !== "undefined" &&
                Hls.isSupported()
            ) {

                const previewHls =
                    new Hls({

                        enableWorker: true,

                        lowLatencyMode: false

                    });


                previewHls.loadSource(
                    videoURL
                );

                previewHls.attachMedia(
                    previewVideo
                );


                /*
                Store HLS instance
                */

                previewVideo.hlsInstance =
                    previewHls;


                /*
                Manifest ready
                */

                previewHls.on(
                    Hls.Events.MANIFEST_PARSED,
                    function () {

                        previewVideo.play().catch(
                            function (error) {

                                console.log(
                                    "Preview HLS autoplay error:",
                                    error
                                );

                            }
                        );

                    }
                );


                /*
                HLS Error
                */

                previewHls.on(
                    Hls.Events.ERROR,
                    function (event, data) {

                        console.log(
                            "Preview HLS Error:",
                            data
                        );

                    }
                );

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

                        previewVideo.play().catch(
                            function (error) {

                                console.log(
                                    "Safari preview autoplay error:",
                                    error
                                );

                            }
                        );

                    },
                    { once: true }
                );

            }


            /*
            HLS unsupported
            */

            else {

                console.log(
                    "HLS is not supported."
                );

            }

        }


        /*
        ====================================
        NORMAL MP4 PREVIEW
        ====================================
        */

        else {

            previewVideo.src =
                videoURL;


            previewVideo.addEventListener(
                "loadeddata",
                function () {

                    previewVideo.play().catch(
                        function (error) {

                            console.log(
                                "MP4 preview autoplay error:",
                                error
                            );

                        }
                    );

                },
                { once: true }
            );


            /*
            Normal video error
            */

            previewVideo.addEventListener(
                "error",
                function () {

                    console.log(
                        "Preview video error:",
                        previewVideo.error
                    );

                },
                { once: true }
            );


            previewVideo.load();

        }

    }


    /*
    ========================================
    CLOSE VIDEO PREVIEW
    ========================================
    */

    function closePreview() {

        if (!previewVideo || !modal) {
            return;
        }


        /*
        Pause video
        */

        previewVideo.pause();


        /*
        Destroy HLS
        */

        destroyHLS(
            previewVideo
        );


        /*
        Remove source
        */

        previewVideo.removeAttribute(
            "src"
        );


        previewVideo.load();


        /*
        Close modal
        */

        modal.classList.remove(
            "active"
        );

    }


    /*
    ========================================
    CLOSE BUTTON
    ========================================
    */

    if (closeVideo) {

        closeVideo.addEventListener(
            "click",
            function () {

                closePreview();

            }
        );

    }


    /*
    ========================================
    CLICK OUTSIDE MODAL
    ========================================
    */

    if (modal) {

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

    }


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
                    modal &&
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