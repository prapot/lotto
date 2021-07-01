if ($('#agent-page').length > 0) {
    const app = new Vue({
        el: '#agent-page',
        data: {
            type: 'backends/agent'
        },
        methods: {
          editData(id){
            window.location.href = baseUrl + '/' + this.type + '/' + id + '/' + 'edit';
          },
          deleteData(id){
          swal({
              title: "Please Confirm ?",
              text: "Do you want to delete this agent !",
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
                swal('Your agent status active');
              }
          });
          }
        }
    });
  }
  if ($('#agent-create').length > 0) {
    const app = new Vue({
        el: '#agent-create'
    });
  }
  if ($('#agent-edit').length > 0) {
    const app = new Vue({
        el: '#agent-edit',
    });
  }
  