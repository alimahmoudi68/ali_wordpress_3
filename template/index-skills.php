<?php 
    $lang = get_current_lang();
?>
<div x-data="skills()" class='w-full flex flex-col items-start mb-[60px] md:mb-[80px]'>
    <div class='flex items-center gap-x-[10px] mb-10'>
        <div class='w-[5px] h-[50px] bg-black-100 dark:bg-textSecondaryDark-100'></div>
        <span class='flex flex-col text-primary-100 text-[2rem] font-bold '>
            <?php echo ( $lang == 'en' ) ? 'My Specialties' : 'تخصص‌های من' ?>
        </span>
    </div>

    <div class='w-full flex flex-wrap justify-center gap-3 mb-8'>
        <template x-for="item in allSkills">
            <div :class="{'border-[3px] filter grayscale dark:border-dark-100':skillSelected !== item.id  , 'border-[3px] border-primary-100':skillSelected == item.id }" class='w-[64px] h-[64px] border cursor-pointer rounded-lg hover:border-primary-100 duration-500' @click="changeSkill(item.id)">
                <img :src="item.img" class='w-full h-auto rounded-md'/>
            </div>
        </template>
    </div>


    <template x-for="item in allSkills">
        <div x-cloak x-show="skillSelected === item.id" class='w-full border border-[3px] border-black-100 dark:border-textSecondaryDark-100 bg-white-100 dark:bg-dark-200 text-slate-700 dark:text-white-100 p-10 rounded-lg'>
            <span x-text="item.description[language]">
            </span>
        </div>
    </template>

</div>


<script>
function skills() {
    return {
        allSkills: [
            {
                id: 1, 
                title: 'Nodejs', 
                img: '<?php echo get_template_directory_uri(); ?>/images/nodejs.png' ,
                description:{
                    fa : 'نود جی اس',
                    en : 'Node JS'
                } 
            },
            {
                id: 3, 
                title: 'Nextjs', 
                img: '<?php echo get_template_directory_uri(); ?>/images/nextjs.png' ,
                description:{
                    fa : 'نکست جی اس',
                    en : 'Next JS'
                } 
            },
            {
                id: 2, 
                title: 'Reactjs', 
                img: '<?php echo get_template_directory_uri(); ?>/images/reactjs.png' ,
                description:{
                    fa : 'ری اکت جی اس',
                    en : 'React JS'
                } 
            },
            {
                id: 4, 
                title: 'Custom Wordpress Theme', 
                img: '<?php echo get_template_directory_uri(); ?>/images/wordpress.png' ,
                description:{
                    fa : 'قالب وردپرس شخصی سازی شده',
                    en : 'Custom Wordpress Theme'
                } ,
            },
            {
                id: 5, 
                title: 'Mongo db', 
                img: '<?php echo get_template_directory_uri(); ?>/images/mongodb.png' ,
                description:{
                    fa : 'مونگو دی بی',
                    en : 'Mongo db'
                } ,
            },
            {
                id: 6, 
                title: 'Tailwind CSS', 
                img: '<?php echo get_template_directory_uri(); ?>/images/tailwind.png' ,
                description:{
                    fa : 'تیلویند',
                    en : 'Tailwind CSS'
                } ,
            }
        ],
        language : "<?php echo $lang ?>" ,
        skillSelected: 1,
        init() {
            // Initialization logic here if needed
        },
        changeSkill(itemId) {
            this.skillSelected = itemId
        },
        // showSkillDescription(){
        //     let skillSelected = this.allSkills.filter(item=>item.id == this.skillSelected);
        //     if(skillSelected.length == 0){
        //         return skillSelected[0].description[this.language];
        //     }
        // },
    };
}
</script>
