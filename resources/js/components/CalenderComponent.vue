<template>
  <div>

    <div id="form-modal">
      <create-component ref="form" @save="saveEvent"></create-component>
    </div>

    <v-menu bottom right>
      <template v-slot:activator="{ on }">
        <v-btn
          outlined
          color="grey darken-2"
          v-on="on"
        >
          <span>{{type}}</span>
        </v-btn>
      </template>

      <v-list>
        <v-list-item @click="type = 'day'">
          <v-list-item-title>Day</v-list-item-title>
        </v-list-item>
        <v-list-item @click="type = 'week'">
          <v-list-item-title>Week</v-list-item-title>
        </v-list-item>
        <v-list-item @click="type = 'month'">
          <v-list-item-title>Month</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-menu>


    <v-sheet height="500">
      <v-calendar
          ref="calendar"
          locale="ja-jp"
          :type="type"
          :now="today"
          :value="today"
          :events="events"
          color="primary"
          @click:event="clickEvent"
          @click:date="showDay"
          @click:day="createEvent"
          @click:more="showDay"
      ></v-calendar>
    </v-sheet>


    <v-menu
        v-model="eventAlert"
        :close-on-content-click="false"
        :activator="selectedItem"
      >
        <v-card
          color="grey lighten-4"
          min-width="350px"
          flat
        >

        <v-card-text>
          <span v-html="selectedEvent.id"></span>
          <span v-html="selectedEvent.name"></span>
        </v-card-text>

        <v-btn
          text
          color="primary"
          @click="eventAlert = false"
        >
          閉じる
        </v-btn>

        <v-btn
          text
          color="error"
          @click="deleteEvent"
        >
          削除
        </v-btn>




        </v-card>
      </v-menu>
  </div>
</template>


<script>
  export default {
    data: () => ({
    today: `2021-06-29`,
    type:'month',
    datas:[],
    events: [
        {
          name: 'あたりまえ体操をする',
          start: '2021-06-29',
          end: '2021-06-30',
          id : 1,
        },
      ],
      eventAlert:false,
      selectedItem : null,
      selectedEvent : {},
    }),
    computed:{

    },
    mounted () {
       // 一覧を取得する
      this.getAllEvent();
      /*
      axios.get('/calendars')
        .then( res => {
          this.events = res.data;
        })
        .catch( e => {
          console.log(e);
        })
      */
    },
    methods:{
      showDay({date}){
        console.log(date);
        this.today = date
        this.type = 'day'
      },
      createEvent(day){
        console.log(day);
           //$refs とはrefで指定した名称のコンポーネントを操作することができる。=>CreateComponent
        this.$refs.form.open(day);
      },
      async saveEvent(params){
        // this.events.push(params);
        await axios.post('/create',params)
          .then( res => {
            console.log(res);
          })
          .catch( e => {
            console.log(e);
          })
          .finally( () => {
            //一覧を取得する
            this.getAllEvent();
          })
          console.log("保存しました。");
      },
      // event → day の順番で呼ばれる。
      clickEvent( {nativeEvent,event} ){
        this.selectedEvent = event;
        this.selectedItem = nativeEvent.target;
        //時間差で動作する。
        setTimeout(() => this.eventAlert = true, 10);

        //次にcreateEventが走ってしまうのを防御する
        nativeEvent.stopPropagation();

      },
      async deleteEvent(){

          const params = {
            id : this.selectedEvent.id
          }


          await axios.post('/delete',params)
            .then( res => {
              alert("削除しました。");
            })
            .catch( e => {
              console.log(e);
            })
            .finally( () => {

            })

          //ついでに閉じる
          this.eventAlert = false;
      },
      // 一覧を取得する
      async getAllEvent(){

        await axios.get('/calendars')
        .then( res => {
          this.events = res.data;
        })
        .catch( e => {
          console.log(e);
        })

      },

    }
  }

</script>
