#Использование FileUploadService

##Допустимые форматы и максимальный размер загружаемых файлов

- Image-файлы
    > jpg, jpeg, png, gif

    > max: 2 MB
- Audio-файлы
    > mp3, wav, aac

    > max: 20 MB
- Video-файлы
    > mp4, avi

    > max: 100 MB


##Для использования сервиса необходимо:
####Сконфигурировать файл [file_upload.php](../config/file_upload.php) по схеме:

```
  'test' => [
          'image_handler' => [
              'handler' => \App\Services\File\Handlers\ImageHandler::class,
              'path' => 'image',
              'procedure' => ['crop', 'watermark'],
          ],
      ],
```
- `test` - сущность к которой загружается файл (course, donate, tariff)
- `image_handler` - для определения типа файла (image_handler, video_handler, audio_handler)
- `handler` - обработчик заданного типа фала
- `path` - название папки для загружаемого файла
- `procedure` - список манипуляций с файлом (crop, watermark) (*если в $request будут crop-параметры, но в `procedure` не будет парамметра 'crop' - тогда сохранится исходное изображение*)

####Вызвать procRequest($request)
```
$this->fileUploadService->procRequest($request);
```
- В метод передать ``Request`` или массив в котором содержится файл

####После валидации, обработки и сохранения файла/файлов сервивс возвращает 
``EloquentCollection``
