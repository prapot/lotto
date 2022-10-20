if ($('#list-sort').length > 0) {
    const app = new Vue({
        el: '#list-sort',
        data: {
            type: 'backends/'+page,
        },
        mounted: function(){
            $( "#sortable" ).sortable();
            $( "#sortable" ).disableSelection();
        },
        methods: {
            save_position(){
              var data = [];
              $('.position').each(function() {
                  data.push($(this).data('order'));
              });
              $.ajax({
                type: 'post',
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url:  baseUrl + '/backends/'+ page +'/position/save',
                data: {
                    data: data,
                },
                success:function(data){
                  // location.href = baseUrl + '/backends/'+page;
                  swal("success", "Item has been sorted", "success");
                },
                error:function(){
                  console.log('error');
                }
              });
            },
        }
    });
  }
  
