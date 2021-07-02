<template>
  <div class="mb-3">
    <button v-if="close_input == 'false'" type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalformula">
      เพิ่มสูตร
    </button>    
    <div class="row mt-3">
        <div class="col-sm-6" v-for="(formula, index) in formulaDatas" :key="index">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">{{formula.title}}</h5>
              <p class="card-text">เลขที่ออกล่าสุด : {{formula.value}}</p>
              <p class="card-text">สูตรที่แสดง : {{formula.result}}</p>
              <img v-if="close_input == 'false'" src="/images/edit.png" width="20" class="position-absolute pointer" style="top: 10px;right: 40px;" @click="beforeEdit(index,formula.id)">
              <img v-if="close_input == 'false'" src="/images/remove.png" width="20" class="position-absolute pointer" style="top: 10px;right: 10px;" @click="beforeRemove(index)">
            </div>
          </div>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalformula" tabindex="-1" role="dialog" aria-labelledby="modalformulaLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalformulaLabel">สูตรหวย</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
          <form>
              <div class="form-group row">
                <label for="title" class="col-sm-2 col-form-label">ชื่อสูตร</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="title" v-model="title" placeholder="ชื่อสูตร">
                </div>
              </div>
              <div class="form-group row">
                <label for="value" class="col-sm-2 col-form-label">ผลที่ออก</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="value"  OnKeyPress="return chkNumber(this)" v-model="value" placeholder="ผลที่ออก">
                </div>
              </div>
              <div class="form-group row">
                <label for="result" class="col-sm-2 col-form-label">ผลลัพธ์ที่แสดง</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="result" v-model="result" placeholder="ผลลัพธ์ที่แสดง">
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
            <button type="button" class="btn btn-primary" @click="submitFormula()">บันทึก</button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>
 
<script>
export default {
  props:['oldformulas','close_input'],
  data () {
    return {
        dataIndex:'',
        id:'',
        title:'',
        value:'',
        result:'',
        formulaDatas:[]
      
    }
  },
  mounted(){
    this.formulaDatas = JSON.parse(this.oldformulas)
  },
  computed:{
  },
  methods: {
      submitFormula(){
          if(this.title.length == 0){
              $('#title').addClass('border border-danger')
          }
          if(this.value.length == 0){
              $('#value').addClass('border border-danger')
          }
          if(this.result.length == 0){
              $('#result').addClass('border border-danger')
          }
          if(this.title.length > 0 && this.value.length > 0  && this.result.length > 0){
              let self = this
              $('#title').removeClass('border border-danger')
              $('#value').removeClass('border border-danger')
              $('#result').removeClass('border border-danger')
              axios.post(baseUrl+'/backends/host/formula', {
                  id:this.id,
                  title: this.title,
                  value: this.value,
                  result: this.result,
              })
              .then(function (response) {
                  let message = 'เพิมสูตรสำเร็จ';
                  if(self.id){
                    message = 'แก้ไขสูตรสำเร็จ'
                    self.formulaDatas[self.dataIndex] = ({"id" : response.data.id,"title":response.data.title,"value":response.data.value,"result":response.data.result})
                  }else{
                    self.formulaDatas.push({"id" : response.data.id ,"title":response.data.title,"value":response.data.value,"result":response.data.result})
                  }
                  self.dataIndex = ''
                  self.id = ''
                  self.title =''
                  self.value = ''
                  self.result = ''
                  swal({
                    'title' : "Success",
                    'text': message,
                    'icon': "success",
                    allowOutsideClick: false
                    })
                  .then((willDelete) => {
                      if (willDelete) {
                          $('#modalformula').modal('hide');
                          $('body').removeClass('modal-open');
                          $('.modal-backdrop').remove();
                      }
                  });
              })
              .catch(function (error) {
                  console.log(error);
              });
          }
      },

      beforeEdit(index,id){
        this.dataIndex = index
        this.id = id;
        this.title = this.formulaDatas[index].title
        this.value = this.formulaDatas[index].value
        this.result = this.formulaDatas[index].result
        $('#modalformula').modal('show');
      },
      beforeRemove (index) {
          let id = this.formulaDatas[index].id;
          swal({
              title: "ยืนยันการลบ",
              text: "ลบรายการนี้หรือไม่",
              icon: "warning",
              buttons: true,
              dangerMode: true,
          })
          .then((willDelete) => {
              if (willDelete) {
                  this.formulaDatas.splice(index, 1)
                  axios.post(baseUrl+'/backends/host/formula/destroy', {
                      id: id
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
 