<template>
  <div class="mb-3">
    <div>
      <div class="row">
          <div class="col-sm-6" v-for="(host, index) in tempHosts" :key="index">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">{{host.name}}</h5>
                <p class="card-text">token : {{host.line_token}}</p>
                <p class="card-text">status : </p>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" :id="'status-time-5-'+index" :name="'status-'+host.id" :checked="toChecked(host.status,'5')" value="5" @click="updateStatus(host.id)">
                  <label class="form-check-label" :for="'status-time-5-'+index">5 นาที</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" :id="'status-time-10-'+index" :name="'status-'+host.id" :checked="toChecked(host.status,'10')" value="10" @click="updateStatus(host.id)">
                  <label class="form-check-label" :for="'status-time-10-'+index">10 นาที</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" :id="'status-time-15-'+index" :name="'status-'+host.id" :checked="toChecked(host.status,'15')" value="15" @click="updateStatus(host.id)">
                  <label class="form-check-label" :for="'status-time-15-'+index">15 นาที</label>
                </div>
                <p class="card-text mt-3">game : </p>
                <div class="row">
                  <div class="col-md-3" v-for="(game,game_index) in dataGames" :key="game_index">
                    <div class="form-check form-check-inline" >
                      <input class="form-check-input" type="checkbox" :id="'status-'+game_index+'-'+index" :name="'status-'+host.id" :checked="toChecked(host.status,game_index)" :value="game_index" @click="updateStatus(host.id)">
                      <label class="form-check-label" :for="'status-'+game_index+'-'+index">{{ game.title }}</label>
                    </div>
                  </div>
                </div>
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
        <div class="col-sm-1">สถานะ</div>
        <div class="col-sm-5">
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="time-5" name="status" value="5">
            <label class="form-check-label" for="time-5">5 นาที</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="time-10" name="status" value="10">
            <label class="form-check-label" for="time-10">10 นาที</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="time-15" name="status" value="15">
            <label class="form-check-label" for="time-15">15 นาที</label>
          </div>
        </div>
      </div>


      <div class="form-group row" v-if="close_input == 'false'">
        <div class="col-sm-1">เกม</div>
        <div class="col-sm-11">
          <div class="row">
            <div class="col-sm-3" v-for="(game,game_index) in dataGames" :key="game_index">
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" :id="'time-'+game_index" name="status" :value="game_index">
                <label class="form-check-label" :for="'time-'+game_index">{{ game.title }}</label>
              </div>
            </div>
          </div>
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
  props:['oldhosts','close_input','games'],
  data () {
    return {
      tempHosts:[],
      dataGames:[]
    }
  },
  mounted(){
    this.tempHosts = JSON.parse(this.oldhosts)
    this.dataGames = JSON.parse(this.games)
  },
  computed:{
  },
  methods: {
    addHost(index){
      let name = $('#name').val();
      let line_token = $('#line_token').val();

      let status = [];
      $.each($("input[name='status']:checked"), function(){
          status.push($(this).val());
      });

      if(name.length == 0){
        $('#name').addClass('border border-danger')
      }
      if(line_token.length == 0){
        $('#line_token').addClass('border border-danger')
      }
      if(name.length > 0 && line_token.length > 0){
        this.tempHosts.push({"name":name,"line_token":line_token,"status":JSON.stringify(status)})
        $('#name').removeClass('border border-danger')
        $('#line_token').removeClass('border border-danger')

        axios.post(baseUrl+'/backends/host', {
            name: name,
            line_token : line_token,
            status : status
        })
        .then(function (response) {
          swal("เพิ่มห้องสำเร็จ", {
              allowOutsideClick: false,
              closeOnClickOutside: false,
              icon: "success",
          })
          .then((willDelete) => {
              if (willDelete) {
                  location.reload()
              }
          });
        })
        .catch(function (error) {
            console.log(error);
        });

      }else{
        return false;
      }
      $('#name').val('');
      $('#line_token').val('');
      $.each($("input[name='status']:checked"), function(){
          $(this).prop( "checked", false );
      });
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
    toChecked(status,time){
        let statusArr = JSON.parse(status);
        let result = _.indexOf(statusArr, time);
        if(result != -1){
          return 'checked';
        }
    },
    updateStatus(id){
      let status = [];
      $.each($("input[name='status-"+id+"']:checked"), function(){
          status.push($(this).val());
      });
      axios.put(baseUrl+'/backends/host/update/status', {
            id : id,
            status : status
        })
        .then(function (response) {
          swal("อัพเดทสำเร็จ", {
              allowOutsideClick: false,
              closeOnClickOutside: false,
              icon: "success",
          })
          .then((willDelete) => {
              if (willDelete) {
                  // location.reload()
              }
          });
        })
        .catch(function (error) {
            console.log(error);
        });
    }
  }
}
</script>
