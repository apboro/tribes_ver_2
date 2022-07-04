<div
    class="col-xl-4 col-lg-5 col-md-5" style=""
    data-tab="mediaContent"
>
    <!-- Project Card -->
    <div class="card position-sticky mb-0" style="top: 100px; height: 74vh; overflow-y: auto">
        <div class="card-header">
            <h3>Медиа контент</h3>
        </div>

        <div class="card-body">
            <div class="accordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button
                            class="accordion-button px-0"
                            data-bs-toggle="collapse"
                            data-bs-target="#accordionImages"
                        >
                            Изображения
                        </button>
                    </h2>

                    <div id="accordionImages" class="accordion-collapse collapse show">
                        <div class="accordion-body p-0">
                            <input
                                type="file"
                                id="images"
                                class="hide"
                                name="images"
                            >
                            
                            <label
                                class="btn btn-outline-dark waves-effect d-block mb-1"
                                for="images"
                            >
                                <i data-feather='upload' class="font-medium-1"></i>
                                <span>Загрузить</span>
                            </label>

                            <div class="row">
                                <div class="col-4">
                                    <img src="/images/no-image.svg" alt="" class="w-100 mb-1">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button px-0" data-bs-toggle="collapse" data-bs-target="#accordionVideo">
                            Видео
                        </button>
                    </h2>

                    <div id="accordionVideo" class="accordion-collapse collapse show">
                        <div class="accordion-body p-0">
                            <input
                                type="file"
                                id="video"
                                class="hide"
                                name="video"
                            >
                            
                            <label
                                class="btn btn-outline-dark waves-effect d-block mb-1"
                                for="video"
                            >
                                <i data-feather='upload' class="font-medium-1"></i>
                                <span>Загрузить</span>
                            </label>

                            <div class="row">
                                <div class="col-4">
                                    <img src="/images/no-image.svg" alt="" class="w-100 mb-1">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button px-0" data-bs-toggle="collapse" data-bs-target="#accordionAudio">
                            Аудио
                        </button>
                    </h2>

                    <div id="accordionAudio" class="accordion-collapse collapse show">
                        <div class="accordion-body p-0">
                            <input
                                type="file"
                                id="audio"
                                class="hide"
                                name="audio"
                            >
                            
                            <label
                                class="btn btn-outline-dark waves-effect d-block mb-1"
                                for="audio"
                            >
                                <i data-feather='upload' class="font-medium-1"></i>
                                <span>Загрузить</span>
                            </label>

                            <div class="row">
                                <div class="col-4">
                                    <img src="/images/no-image.svg" alt="" class="w-100 mb-1">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
