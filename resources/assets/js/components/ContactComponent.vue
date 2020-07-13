<template>

     <b-list-group-item :variant="variantg">

            <b-row class="p-2" align-h="center">

                <b-col cols="12" md="3" class="text-center">
                    <b-img rounded="circle" blank width="60" height="60" blank-color="#777" alt="Circle image"></b-img>
                </b-col>

                <b-col cols="6" align-self="center" class="d-none d-md-block">
                    <p class="mb-1">{{name}}</p>
                    <p class="text-muted small mb-0">{{lastMessage}}</p>
                </b-col>

                <b-col cols="3" class="d-none d-md-block">
                    <p class="text-muted small">{{lastTime}}</p>
                </b-col>

            </b-row>

        </b-list-group-item>

</template>

<script>
    export default {
        props:[
            'variantg'
        ],
        data()
        {
            return{
                name: 'Juan Ramos',
                lastMessage: 'Tú: Hasta Luego',
                lastTime: '1:58 pm',
                variant: 'Dark'


            };

        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
