var app = new Vue({
    el: '#app',
    data: {
        currentSlide: 0,
        isPreviousSlide: false,
        isFirstLoad: true,
        slides: [
            {
                headlineFirstLine: "ЗАГОЛОВОК №1",
                headlineSecondLine: "подЗАГОЛОВОК №2",
                sublineFirstLine: "Главная",
                sublineSecondLine: "О нас",
                bgImg: "/images/vue/about-bg.jpg",
                rectImg: "/images/vue/leadership_hero_feature.jpg"
            },
            {
                headlineFirstLine: "ЗАГОЛОВОК №2",
                headlineSecondLine: "подЗАГОЛОВОК №2",
                sublineFirstLine: "О нас",
                sublineSecondLine: "Проекты",
                bgImg: "/images/vue/ukrdeveloperbackgroundfullhd.jpg",
                rectImg: "/images/vue/skyfalling.jpg"
            },
            {
                headlineFirstLine: "ЗАГОЛОВОК №3",
                headlineSecondLine: "подЗАГОЛОВОК №3",
                sublineFirstLine: "О нас",
                sublineSecondLine: "Стоимость разработки",
                bgImg: "/images/vue/about.jpg",
                rectImg: "/images/vue/sly10.jpg"
            }
        ]
    },
  mounted: function () {
    var productRotatorSlide = document.getElementById("app");
        var startX = 0;
        var endX = 0;

        productRotatorSlide.addEventListener("touchstart", (event) => startX = event.touches[0].pageX);

        productRotatorSlide.addEventListener("touchmove", (event) => endX = event.touches[0].pageX);

        productRotatorSlide.addEventListener("touchend", function(event) {
            var threshold = startX - endX;

            if (threshold < 150 && 0 < this.currentSlide) {
                this.currentSlide--;
            }
            if (threshold > -150 && this.currentSlide < this.slides.length - 1) {
                this.currentSlide++;
            }
        }.bind(this));
  },
    methods: {
        updateSlide(index) {
            index < this.currentSlide ? this.isPreviousSlide = true : this.isPreviousSlide = false;
            this.currentSlide = index;
            this.isFirstLoad = false;
        }
    }
})
