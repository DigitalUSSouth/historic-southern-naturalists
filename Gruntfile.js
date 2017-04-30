/**
 * Gruntfile.js
 */

module.exports = function (grunt) {
  grunt.initConfig({
    browserSync: {
      default: {
        bsFiles: {
          src: [
            'css/*.css',
            'js/*.js',
            '**/*.php',
            '!.scripts/*'
          ]
        },

        options: {
          open:      false,
          proxy:     'localhost:8000',
          notify:    true,
          watchTask: true
        }
      }
    },

    concat: {
      default: {
        src:  ['js/bootstrap/*.js'],
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
          'css/hsn-1.0.0.min.css': 'css/hsn/hsn.less',
          'css/bootstrap-3.3.7.min.css': 'css/bootstrap/bootstrap.less',
          'css/font-awesome-4.6.3.min.css': 'css/font-awesome/font-awesome.less'
        }
      }
    },

    php: {
      default: { }
    },

    watch: {
      less: {
        files: ['css/**/*.less'],
        tasks: ['less']
      },

      scripts: {
        files: '<%= concat.default.src %>',
        tasks: ['concat']
      }
    }
  });

  grunt.loadNpmTasks('grunt-browser-sync');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-php');

  grunt.registerTask('dev', [
    'php',
    'browserSync',
    'watch'
  ]);

  grunt.registerTask('default', [
    'concat',
    'less'
  ]);
};
