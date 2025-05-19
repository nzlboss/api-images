# 随机图片API文档

这个PHP脚本实现了一个随机图片API，支持根据图片尺寸分类、图片大小调整等功能。通过简单配置，你可以轻松搭建一个属于自己的随机图片服务。

## 功能特点

- 支持按图片尺寸分类（600×600以下为小图，600×600以上为大图）
- 可随机返回不同尺寸的图片
- 支持指定图片尺寸参数，动态调整图片大小
- 自动处理常见图片格式（JPG、PNG、GIF）
- 图片不存在时返回友好的默认占位图

## 安装步骤

1. 将PHP脚本保存为`api.php`并上传至你的Web服务器
2. 在脚本同级目录下创建`image`目录
3. 在`image`目录下分别创建`small`和`large`子目录
4. 将小图片（600×600以下）放入`small`目录
5. 将大图片（600×600以上）放入`large`目录

目录结构示例：
```
your-server/
├── api.php
└── image/
    ├── small/
    │   ├── small1.jpg
    │   ├── small2.png
    │   └── ...
    └── large/
        ├── large1.jpg
        ├── large2.png
        └── ...
```

## 参数说明

| 参数名 | 类型 | 描述 | 是否必填 | 示例 |
|--------|------|------|----------|------|
| width  | 整数 | 期望的图片宽度（像素） | 否 | width=800 |
| height | 整数 | 期望的图片高度（像素） | 否 | height=600 |
| size   | 字符串 | 指定图片尺寸类型：`small`（小图）或`large`（大图） | 否 | size=large |

## 使用示例

### 1. 获取随机小图片（原始尺寸）
```html
<img src="http://yourserver.com/api.php?size=small" alt="随机小图片">
```

### 2. 获取随机大图片（调整为800×600）
```html
<img src="http://yourserver.com/api.php?size=large&width=800&height=600" alt="随机大图片">
```

### 3. 获取任意尺寸随机图片（调整为1200×900）
```html
<img src="http://yourserver.com/api.php?width=1200&height=900" alt="随机图片">
```

### 4. 获取任意尺寸随机图片（原始尺寸）
```html
<img src="http://yourserver.com/api.php" alt="随机图片">
```

## 响应说明

- 成功请求：返回图片文件（根据请求参数调整尺寸或原始尺寸）
- 无匹配图片：返回SVG格式的默认占位图
- 错误情况：返回HTTP 500错误（需服务器配置支持）

## 响应式设计建议

在HTML中使用此API时，建议结合CSS实现响应式设计，确保图片在不同设备上都有良好的显示效果：

```html
<style>
    .responsive-image {
        width: 100%;              /* 图片宽度占满父容器 */
        height: auto;             /* 保持图片比例 */
        max-height: 800px;        /* 最大高度限制 */
        object-fit: cover;        /* 图片填充方式 */
        display: block;           /* 移除图片底部间隙 */
        margin: 0 auto;           /* 居中显示 */
    }
    
    @media (max-width: 768px) {
        .responsive-image {
            max-height: 400px;    /* 在小屏幕上减小高度 */
            object-fit: contain;  /* 确保图片完全可见 */
        }
    }
</style>

<img 
    src="http://yourserver.com/api.php?width=1920&height=800" 
    alt="随机图片" 
    class="responsive-image"
    loading="lazy" <!-- 图片懒加载 -->
>
```

## 注意事项

1. 请确保服务器已启用GD库扩展，否则无法进行图片尺寸调整
2. 图片目录权限需要设置为可读取
3. 如需处理更多图片格式，可在`glob`函数中添加相应扩展名
4. 默认占位图尺寸为800×600，可在代码中修改

## 常见问题

1. **问**：为什么返回的是占位图？  
   **答**：可能是`small`或`large`目录为空，请确保目录中有图片文件。

2. **问**：如何调整默认占位图的样式？  
   **答**：编辑代码中最后一个`echo`语句中的SVG代码即可修改占位图样式。

3. **问**：是否支持WebP格式？  
   **答**：默认不支持，如需支持WebP，需在`glob`函数中添加`webp`扩展名，并在输出处理中添加相应的处理逻辑。
