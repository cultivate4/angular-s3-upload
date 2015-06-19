# Angular S3 Upload

Angular Amazon Web Services (AWS) S3 Upload Directive. Based off [angular-aws-s3-upload](https://github.com/Serhioromano/angular-aws-s3-upload) but with some modifications.

## Installation

Install with bower.

    bower install ng-s3-upload --save

```js
<script src="bower_components/angular/angular.js"></script>
<script src="bower_components/angular-s3-upload/dist/angular-s3-upload.min.js"></script>
```

If you want to use `ngMessages` with this element you have to add this to your project.

And add the dependency to your application.

```js
angular.module('app', ['ngS3Upload']);