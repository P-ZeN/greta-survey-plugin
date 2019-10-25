(function() {
    'use strict';
}());
module.exports = function(grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),


        compass: {
            dist: {
                options: {
                    sassDir: 'scss',
                    cssDir: '../css',
                    environment: 'production',
                    outputStyle: 'compressed'
                }
            }
        },

        watch: {
            files: ['scss/**/*.scss'],
            tasks: ['compass']
                // tasks: ['concat', 'uglify', 'jshint', 'compass']
        }

    });

    grunt.loadNpmTasks('grunt-contrib-compass');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.registerTask('default', ['compass', 'watch']);
};