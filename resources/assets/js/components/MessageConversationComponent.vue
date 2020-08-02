<template>

    <b-media :right-align="writtenByMe" vertical-align="center" class="mb-2">
        <template v-slot:aside>
        <b-img rounded="circle" blank blank-color="#ccc" width="48" alt="placeholder"></b-img>
        </template>
        <b-card>
            <p class="mb-0">
            <slot></slot>
            </p>
        </b-card>
    </b-media>

</template>

<script>
    export default {
        props: {
                    writtenByMe : Boolean
                },
        data()
        {
            return{
            
            };

        },
        mounted() {

        }
    }
</script>
