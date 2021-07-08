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
              <p class="card-text">สูตร : <span v-if="formula.type == 3">สามตัวบน</span><span v-if="formula.type == 2">สองตัวล่าง</span></p>
              <p class="card-text">เลขที่ออกล่าสุด : 
                <span v-for="(dataValue , data_index ) in formula.values" :key="data_index">
                {{dataValue.value}}
                <span v-if="data_index != Object.keys(formula.values).length - 1">, </span>
                </span>
              </p>
              <p class="card-text" v-if="formula.condition != 0">ขั้นสูง : เลขที่เลือกไว้ติดกัน มากกว่า {{formula.round}} รอบ ใน {{formula.last_round}} รอบล่าสุด</p>
              <p class="card-text">สูตรที่แสดง : {{formula.result}}</p>
              <!-- <img v-if="close_input == 'false'" src="/images/edit.png" width="20" class="position-absolute pointer" style="top: 10px;right: 40px;" @click="beforeEdit(index,formula.id)"> -->
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
                <label class="col-sm-2 col-form-label">สูตรเช็ค</label>
                <div class="col-sm-10  d-flex align-items-center">
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="3upper" name="type" class="custom-control-input" value="3" v-model="type" @click="changeType()">
                    <label class="custom-control-label" for="3upper">สามตัวบน</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="2under" name="type" class="custom-control-input" value="2" v-model="type" @click="changeType()">
                    <label class="custom-control-label" for="2under">สองตัวล่าง</label>
                  </div>
                </div>
              </div>

              <div class="form-group row" v-if="type">
                <label for="value" class="col-sm-2 col-form-label">เลขที่เลือก</label>
                <div class="col-sm-10 row">
                  <div class="col-sm-2" v-for="(value, index_value) in values" :key="index_value" :class="(values.length > 6)?'mt-3':''">
                    <input type="text" class="form-control value"  OnKeyPress="return chkNumber(this)" v-model="value.value" placeholder="ผลที่ออก">
                  </div>
                  <div class="col-sm-2 d-flex align-items-center" :class="(values.length > 5)?'mt-3':''">
                    <img v-if="close_input == 'false'" src="/images/plus.png" class="pointer" width="20" @click="addValue()">
                  </div>
                </div>
              </div>

              <div class="form-group row" v-if="type">
                <label class="col-sm-2 col-form-label">เงื่อนไขขั้นสูง</label>
                <div class="col-sm-10 d-flex align-items-center">
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="checkbox" value="0" v-model="condition">
                    <label class="custom-control-label" for="checkbox">เลขที่เลือกไว้ติดกัน มากกว่า</label>
                  </div>
                </div>
              </div>

              <div class="form-group row" v-if="condition">
                <label class="col-sm-2 col-form-label">เลขที่เลือกไว้ติดกัน มากกว่า</label>
                <div class="col-sm-3">
                  <select class="form-control" id="round" v-model="round">
                    <option disabled value="">เลือกรอบที่พบ</option>
                    <!-- <option v-for="(round,index_round) in rounds" :key="index_round" :value="index_round + 1">{{index_round+1}}</option> -->
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                  </select>
                </div>
                <div class="col-sm-4">
                  <select class="form-control" id="last_round" v-model="last_round">
                    <option disabled value="">เลือกในรอบล่าสุด</option>
                    <!-- <option v-for="(last_round,index_lastround) in last_rounds" :key="index_lastround" :value="index_lastround + 1">{{index_lastround+1}}</option> -->
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    
                  </select>
                </div>
              </div>

              <!-- <div class="form-group row" v-if="type">
                <label for="result" class="col-sm-2 col-form-label">ข้อความที่แสดง</label>
                <div class="col-sm-10">
                  <textarea class="form-control" id="result" v-model="result" placeholder="ข้อความที่แสดง"></textarea>
                </div>
              </div> -->


              <div class="form-group row" v-if="type">
                <label for="result" class="col-sm-2 col-form-label">ข้อความที่แสดง</label>
                  <div class="col-sm-10">
                    <div class="wrapper">
                      <textarea class="form-control" id="result" v-model="result" placeholder="ข้อความที่แสดง"></textarea>
                  <emoji-picker @emoji="append" :search="search">
                    <div
                      class="emoji-invoker"
                      slot="emoji-invoker"
                      slot-scope="{ events: { click: clickEvent } }"
                      @click.stop="clickEvent"
                    >
                      <svg height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 0h24v24H0z" fill="none"/>
                        <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/>
                      </svg>
                    </div>
                    <div slot="emoji-picker" slot-scope="{ emojis, insert, display }">
                      <div class="emoji-picker" :style="{ top: display.y + 'px', left: display.x + 'px' }">
                        <div class="emoji-picker__search">
                          <input type="text" v-model="search" v-focus>
                        </div>
                        <div>
                          <div v-for="(emojiGroup, category) in emojis" :key="category">
                            <h5>{{ category }}</h5>
                            <div class="emojis">
                              <span
                                v-for="(emoji, emojiName) in emojiGroup"
                                :key="emojiName"
                                @click="insert(emoji)"
                                :title="emojiName"
                              >{{ emoji }}</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </emoji-picker>
                </div>
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
import EmojiPicker from 'vue-emoji-picker'

export default {
  props:['oldformulas','close_input'],
  data () {
    return {
        validate:[],
        validate_round:0,
        validate_last_round:0,
        dataIndex:'',
        id:'',
        title:'',
        type:'',
        condition:0,
        rounds:10,
        last_rounds:10,
        round:0,
        last_round:0,
        values:[],
        result:'',
        formulaDatas:[],
        search: ''
      
    }
  },
  components: {
    EmojiPicker,
  },
  mounted(){
    this.formulaDatas = JSON.parse(this.oldformulas)
    this.values.push({ value: '' });
  },
  computed:{
  },
  methods: {
      append(emoji) {
        this.result += emoji
      },
      directives: {
        focus: {
          inserted(el) {
            el.focus()
          },
        },
      },
      addValue() {
        this.values.push({ value: '' });
      },
      changeType(){
        this.values = [];
        this.values.push({ value: '' });
      },
      submitFormula(){
          let self = this
          if(this.title.length == 0){
              $('#title').addClass('border border-danger')
          }

          this.validate = []
          $.each(this.values, function(key, value) {
            if(value.value == '' || value.value.length != parseInt(self.type)){
              self.validate.push('1')
            }else{
              self.validate.push('0')
            }
          });
          if(this.validate.indexOf("1") !== -1){
            $('.value').addClass('border border-danger')
          }else{
            $('.value').removeClass('border border-danger')
            this.validate = 0
          }

          if(this.result.length == 0){
              $('#result').addClass('border border-danger')
          }


          if(this.condition == 1){
            if(this.round <= 0){
              $('#round').addClass('border border-danger')
              this.validate_round = 1
            }else{
              $('#round').removeClass('border border-danger')
              this.validate_round = 0
            }
            if(this.last_round <= 0){
              $('#last_round').addClass('border border-danger')
              this.validate_last_round = 1
            }else{
              $('#last_round').removeClass('border border-danger')
              this.validate_last_round = 0
            }
          }

          if(this.title.length > 0 && this.validate == 0 && this.result.length > 0 && this.validate_round == 0 && this.validate_last_round == 0){
              $('#title').removeClass('border border-danger')
              $('#result').removeClass('border border-danger')
              $('#round').removeClass('border border-danger')
              $('#last_round').removeClass('border border-danger')
              axios.post(baseUrl+'/backends/host/formula', {
                  id:this.id,
                  title: this.title,
                  type: this.type,
                  condition:this.condition,
                  round:this.round,
                  last_round:this.last_round,
                  values: this.values,
                  result: this.result,
              })
              .then(function (response) {
                  let message = 'เพิมสูตรสำเร็จ';
                  if(self.id){
                    message = 'แก้ไขสูตรสำเร็จ'
                    self.formulaDatas[self.dataIndex] = ({"id" : response.data.id,"title":response.data.title,"condition":response.data.condition,"round":response.data.round,"last_round":response.data.last_round,"values":response.data.values,"result":response.data.result})
                  }else{
                    self.formulaDatas.push({"id" : response.data.id ,"title":response.data.title,"type":response.data.type,"condition":response.data.condition,"round":response.data.round,"last_round":response.data.last_round,"values":response.data.values,"result":response.data.result})
                  }
                  self.dataIndex = []
                  self.dataIndex = ''
                  self.id = ''
                  self.title =''
                  self.type =''
                  self.condition =0
                  self.round =0
                  self.last_round =0
                  self.values = [{'value':''}]
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
 