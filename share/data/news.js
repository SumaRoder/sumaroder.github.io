// 将form.serialize() 转json
function formToJson (data) {
    data = decodeURIComponent(data)  // 解码
    data=data.replace(/&/g,"\",\"");
    data=data.replace(/=/g,"\":\"");
    data="{\""+data+"\"}";
    return data;
}

var newsArr = [];
// 新增/修改新闻
Mock.mock('/news/save', /post/i, (options) => {
    let params = formToJson(options.body); // 因为示例ajax提交都是表单serialize获取的数据，这里将其转为json
    let news = JSON.parse(params);
    if (news.id == null || news.id == 0) {
        news.id = Mock.Random.increment();
        newsArr.push(news);
    } else {
        for (let i = 0; i < newsArr.length; i++) {
            if (newsArr[i].id == news.id) {
                newsArr.splice(i, 1);
                newsArr.push(news);
            }
        }
    }
    return { code: '200', msg: news.id ? '新增成功' : '编辑成功' };
})

// 获取所有新闻
Mock.mock('/news/list', /post/i, (options) => {
    let info = JSON.parse(options.body);
    if (!info.page) {
        info.page = 1;
    }

    let newNewsArr = [];
    if (newsArr.length > 0) {
        let page = (info.page - 1) * info.limit;
        // 计算截取的数组长度，如果用splice会导致原数组数据丢失
        let num = info.page * info.limit;
        for (let i = page; i < num; i++) {
            // 全部数据的数组长度不能为空
            if (newsArr[i] != undefined) {
                // 查询条件为单词时将符合的数据添加到新的数组内
                if (info.title && !newsArr[i].title.indexOf(info.title)) {
                    newNewsArr.push(newsArr[i]);
                }
                // 查询条件为空时添加所有数据到新数组
                if (!info.title) {
                    newNewsArr.push(newsArr[i]);
                }
            }
        }
        let returnData = { rows: newNewsArr, total: newsArr.length };
        return returnData;
    }
    let returnData = { rows: newsArr, total: newsArr.length };
    return returnData;
});

// 根据ID获取一条新闻内容
Mock.mock('/news/getInfo', /post/i, (options) => {
    // 不知道为啥不是json格式的
    let params = formToJson(options.body);
    let info = JSON.parse(params);
    if (!info.id) {
        return { code: '500', msg: '参数错误' };
    }
    
    var returnData;
    if (newsArr.length > 0) {
        for (let i = 0; i < newsArr.length; i++) {
            // 全部数据的数组长度不能为空
            if (newsArr[i] != undefined) {
                // 查询条件为单词时将符合的数据添加到新的数组内
                if (info.id && info.id == newsArr[i].id) {
                    returnData = newsArr[i];
                }
            }
        }
    } else {
        return { code: '404', msg: '未找到数据' };
    }
    
    return { code: '200', msg: '成功', data: returnData };
});

// 删除新闻
Mock.mock('/news/delete', /post/i, (options) => {
    // 不知道为啥不是json格式的
    let params = formToJson(options.body);
    let info = JSON.parse(params);
    var idsArr = info.ids.split(",");

    for (let i = 0; i < newsArr.length; i++) {
        for (let j = 0; j < idsArr.length; j++) {
            if (newsArr[i].id == idsArr[j]) {
                newsArr.splice(i, 1);
            }
        }
    }
    
    return { code: '200', msg: '删除成功' };
});

// 开启/禁用
Mock.mock('/news/setStatus', /post/i, (options) => {
    // 不知道为啥不是json格式的
    let params = formToJson(options.body);
    let info = JSON.parse(params);
    var idsArr = info.ids.split(",");

    for (let i = 0; i < newsArr.length; i++) {
        for (let j = 0; j < idsArr.length; j++) {
            if (newsArr[i].id == idsArr[j]) {
                newsArr[i].status = info.status;
            }
        }
    }
    
    return { code: '200', msg: (info.status ? '开启' : '禁用') + '成功' };
});