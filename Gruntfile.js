/*
 * Copyright © 2018  Nicolas Gnyra
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

module.exports = function (grunt) {
    grunt.initConfig({
        sass: {
            options: {
                outputStyle: 'compressed',
                sourceMap: true
            },
            dist: {
                files: [{
                    expand: true,
                    cwd: 'dist/scss',
                    src: ['*.scss'],
                    dest: 'dist/css',
                    ext: '.min.css'
                }]
            }
        },
        autoprefixer: {
            dist: {
                options: {
                    map: true
                },
                files: [{
                    expand: true,
                    cwd: 'dist/css',
                    src: ['*.min.css'],
                    dest: 'dist/css',
                    ext: '.min.css'
                }]
            }
        },
        uglify: {
            options: {
                mangle: true,
                sourceMap: true
            },
            dist: {
                files: {
                    'dist/js/script.min.js': ['dist/js/script.js']
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('grunt-autoprefixer');
    grunt.loadNpmTasks('grunt-contrib-uglify');

    grunt.registerTask('default', ['sass', 'autoprefixer', 'uglify']);
};