module.exports = function (grunt) {
  grunt.initConfig({
    concat: {
      default: {
        src: ['js/bootstrap/*.js'],
        dest: 'js/bootstrap-3.3.7.min.js'
      }
    },

    less: {
      default: {
        options: {
          compress:  true,
          sourceMap: true
        },
        files: {
          'css/bootstrap-3.3.7.min.css': 'css/bootstrap/bootstrap.less',
          'css/font-awesome-4.6.3.min.css': 'css/font-awesome/font-awesome.less'
        }
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-less');

  grunt.registerTask('default', ['concat', 'less']);
};
