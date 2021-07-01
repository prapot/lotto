if ($('#admin-page').length > 0) {
    const app = new Vue({
        el: '#admin-page',
        data: {
            type: 'backends/admin'
        },
        methods: {
          editData(id){
            window.location.href = baseUrl + '/' + this.type + '/' + id + '/' + 'edit';
          },
          deleteData(id){
          swal({
              title: "Please Confirm ?",
              text: "Do you want to delete this admin !",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                axios.post(baseUrl +'/'+ this.type + '/destroy',{
                  headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  },
                  id : id
                })
                .then(function (response) {
                    swal('delete successful', {
                        icon: "success",
                      })
                      .then((value) => {
                        location.reload();
                      });
                })
                .catch(function (error) {
                    console.log('error');
                });
              } else {
                swal('Your admin status active');
              }
          });
          }
        }
    });
  }
  if ($('#admin-create').length > 0) {
    const app = new Vue({
        el: '#admin-create'
    });
  }
  if ($('#admin-edit').length > 0) {
    const app = new Vue({
        el: '#admin-edit',
    });
  }
  