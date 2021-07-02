<template>
  <div class="mb-3">
    <div>
      <div class="row">
          <div class="col-sm-6" v-for="(host, index) in tempHosts" :key="index">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">{{host.name}}</h5>
                <p class="card-text">token : {{host.line_token}}</p>
                <img v-if="close_input == 'false'" src="/images/remove.png" width="20" class="position-absolute pointer" style="top: 10px;right: 10px;" @click="beforeRemove(index)">
              </div>
            </div>
        </div>
      </div>
      
      <div class="form-group row" v-if="close_input == 'false'">
        <label for="name" class="col-sm-1 col-form-label">ชื่อห้อง</label>
        <div class="col-sm-5">
          <input type="text" class="form-control" id="name" placeholder="ชื่อห้อง">
        </div>
      </div>

      <div class="form-group row" v-if="close_input == 'false'">
        <label for="line_token" class="col-sm-1 col-form-label">โทเคน</label>
        <div class="col-sm-5">
          <input type="text" class="form-control" id="line_token" placeholder="โทเคน Line">
        </div>
      </div>

      <div class="form-group row" v-if="close_input == 'false'">
        <div class="col-sm-10">
            <button class="btn btn-outline-primary" type="button" @click="addHost()">เพิ่มห้อง</button>
        </div>
      </div>
    </div>
  </div>
</template>
 
<script>
export default {
  props:['oldhosts','close_input'],
  data () {
    return {
      tempHosts:[]
    }
  },
  mounted(){
    this.tempHosts = JSON.parse(this.oldhosts)
  },
  computed:{
  },
  methods: {
    addHost(index){
      let name = $('#name').val();
      let line_token = $('#line_token').val();
      if(name.length == 0){
        $('#name').addClass('border border-danger')
      }
      if(line_token.length == 0){
        $('#line_token').addClass('border border-danger')
      }
      if(name.length > 0 && line_token.length > 0){
        this.tempHosts.push({"name":name,"line_token":line_token})
        $('#name').removeClass('border border-danger')
        $('#line_token').removeClass('border border-danger')

        axios.post(baseUrl+'/backends/host', {
            name: name,
            line_token : line_token
        })
        .then(function (response) {
            swal("Success", "เพิ่มห้องสำเร็จ", "success");
        })
        .catch(function (error) {
            console.log(error);
        });

      }else{
        return false;
      }
      $('#name').val('');
      $('#line_token').val('');
    },
    beforeRemove (index) {
        let host_id = this.tempHosts[index].id;
        swal({
            title: "ยืนยันการลบ",
            text: "ลบรายการนี้หรือไม่",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                this.tempHosts.splice(index, 1)
                axios.post(baseUrl+'/backends/host/destroy', {
                    id: host_id
                })
                .catch(function (error) {
                    console.log('delete :',error);
                });
                swal("ลบสำเร็จ", {
                    icon: "success",
                });
            }
        });
    },
  }
}
</script> 
 