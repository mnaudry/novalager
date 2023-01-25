<template>
    <div class="product">
        <h1 class="head">Fiche produit</h1>
        <div class="alert error" v-if="errors.length">
            <p v-for="(error,index) of errors " :key="index">{{ error }}</p>
        </div>
        <div v-if="product" class="poduct-info">
            <div class="header">{{ product.title }}</div>
            <div class="body">
                <p class="description">{{product.description}}</p>
                <img class="image" :src="`../../storage/${product.image}`" :alt="product.title">
                <a class="pdf" :href="`../../storage/${product.pdf}`" :download="`${this.$route.params.slug}.pdf`" v-text="`Click ici pour télécharger la fiche produit en pdf`" :alt="product.title" />
                <p>
                    Lien Amazon : <a href="" v-text="storeUrl"  :alt="product.title"/>
                </p>
            </div>
        </div>
        <div v-if="showMessage" class="alert message">{{message}}</div>
    </div>
</template>
<script>
    import axios from 'axios';
    import moment from 'moment-timezone';
    export default {
        name:"Product",
        data() {
            return {
                product:null,
                errors:[],
                message:"Fiche produit en cours de chargement...",
                showMessage : true,
                topLevelDomain : "com",
                amazon_slug : "",
                caTimeZone : [],
                timeZone: "",
            }
        },

        computed : {
            storeUrl() {
                return `https://amazon.${this.topLevelDomain}/${this.amazon_slug}`;
            }
        },

        
        /*beforeRouteEnter(to,from,next) {
    
            axios.get(`/api/products/${to.params.slug}/product`,  { headers : {
                        'Accept':'application/json'
                    }
            }).then((response) => {
           
               next(vm => vm.fill(response));
            }).catch((error) => {

                 next(vm => vm.error(error));
            })

        },*/

        created() {

            this.$watch(
                () => this.$route.params,
                () => {
                    this.getProduct();
                },
                // fetch the data when the view is created and the data is
                // already being observed
                { immediate: true }


            )
        },


        methods:{

            getProduct() {
                    axios.get(`/api/products/${this.$route.params.slug}/product`,  { headers : {
                                'Accept':'application/json'
                            }
                    }).then((response) => {
                
                    // next(vm => vm.fill(response));
                        this.fill(response);
                    }).catch((error) => {
                        this.error(error);
                        //next(vm => vm.error(error));
                    })
            },

             fill(response) {
                 if(response.data.product) {
                    this.showMessage = false; 
                    this.product = {... response.data.product};
                    this.amazon_slug = this.product.amz_url ;
              
                    //this.timeZone = "America/Sitka" 
                    this.timeZone =  Intl.DateTimeFormat().resolvedOptions().timeZone ;
                    this.caTimeZone = moment.tz.zonesForCountry('CA') ;
                    //this.usTimeZone = moment.tz.zonesForCountry('US') ;
                    this.findTopLevelDomain();
                }
                else {
                    this.message = "Le produit n'est pas disponible"
                    this.showMessage = true ;
                }
            },

            error(error) {
                //this.$router.push({name : 'NotFound'})
                this.showMessage = false; 
                if(error.response.status == 404) {
                    this.errors.push("Le produit demandé n'a pas été trouvé");
                } else  {
                    this.errors.push("Nous rencontrons actuellement des difficultes pour afficher le produit, veuillez réessayer ultérieurement");
                }
            },

            findTopLevelDomain() {

                // ca for canada and com for others
                let find = false ;
                //console.log(this.timeZone);
                  // this.topLevelDomain = "com";
                find = this.caTimeZone.some((timezone) => timezone == this.timeZone);
                if(find) {
                    this.topLevelDomain = "ca";
                }

            }
        }


    }
</script>
<style lang="scss">
.product {

    .poduct-info {
        .header {
                    text-transform: uppercase;
                    font-weight: 700;
        }

        .image {
                width: 200px;
                display: block;
        }

        .pdf {
            display:block;
            margin-top: 1rem;
        }
    }
   
}
</style>