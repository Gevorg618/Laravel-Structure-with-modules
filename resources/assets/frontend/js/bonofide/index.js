$(function() {
  const carouselItem = $(".carousel-inner");

  const changeImage = (size = "mobile") => {
    const image = `${size}_image`;
    $(carouselItem.children()).each((index, value) => {
      GlobalScope.header_carousel.map(item => {
        if (item.id == $(value).attr("data-id")) {
          $(value).css({
            "background-image": `url(${item[image]})`,
            "background-repeat": "no-repeat",
            "background-size": "cover"
          });
        }
      });
    });
  };
  if ($(window).width() >= 991) {
    changeImage("desktop");
  } else {
    changeImage();
  }
  $(window).on("resize", function(e) {
    if ($(this).width() <= 991) {
      changeImage();
    } else {
      changeImage("desktop");
    }
  });
});
