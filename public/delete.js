

(function(window, document, undefined){

  // code that should be taken care of right away
  
  window.onload = init;
  
    function init(){
      const products = document.getElementById('products');
      if (products) {
        products.addEventListener('click', e => {
          if (e.target.className === 'btn btn-danger delete-product') {
            if (confirm('Are you sure?')) {
              const id = e.target.getAttribute('data-id');

              fetch(`/prueba/public/product/delete/${id}`, {
                method: 'DELETE'
              }).then(res => window.location.reload());
            }
          }
        });
      }
    }
  
  })(window, document, undefined);