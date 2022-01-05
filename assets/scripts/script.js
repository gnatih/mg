;(function ($) {
  $(function () {
    $('.mg-ajax-add-to-cart-button').on('click', function (e) {
      e.preventDefault()

      let $this = $(this)
      let pid = $(this).data('product-id')
      let data = {
        action: 'mg_add_to_cart',
        product_id: pid,
      }

      $.ajax({
        type: 'post',
        url: ajaxurl,
        data: data,
        beforeSend: (response) => {
          $this.removeClass('added').addClass('loading')
        },
        complete: (response) => {
          $this.addClass('added').removeClass('loading')
        },
        success: (response) => {
          if (response.error & response.product_url) {
            console.log('has error', response.error)
            window.location = response.product_url
            return
          } else {
            window.location.reload()
          }
        },
      })

      return false
    })
  })
})(jQuery)
