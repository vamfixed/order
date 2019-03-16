<template>
    <div class="container">
        <div class="row">
          <div class="col-lg-4 col-6" v-for="(item, index) in stats" :key="index">
            <!-- small box -->
            <div :class="'small-box bg-' + item.color">
              <div class="inner">
                <h3>{{item.value}}</h3>

                <p>{{item.title}}</p>
              </div>
              <div class="icon">
                <i :class="item.icon"></i>
              </div>
              <router-link :to="item.link" class="small-box-footer">View <i class="fa fa-arrow-circle-right"></i></router-link>
            </div>
          </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                stats: [
                   
                ]
            }
        },
        created() {
            this.$Progress.start();
            axios.get('/api/stats')
            .then(res => {
                this.stats = res.data;
                this.$Progress.finish();
            })
            .catch(err => {
                console.log(err);
                this.$Progress.fail();
            })
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
